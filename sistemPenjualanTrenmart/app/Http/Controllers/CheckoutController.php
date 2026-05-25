<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\BerandaSetting;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        
        // 1. Ambil item keranjang milik user
        $cartItems = Keranjang::where('user_id', $user_id)->get();

        // Jika keranjang kosong, balikkan ke halaman keranjang
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // 2. Hitung Total (Logic sesuai diskusi kita sebelumnya)
        $total = 0;
        foreach ($cartItems as $item) {
            $harga = $item->bundling_id 
                     ? $item->bundling->bundling_price 
                     : ($item->harga_at_time ?? $item->produk->harga_jual_umum);
            $total += $harga * $item->jumlah;
        }

        // 3. Ambil Metode Pembayaran
        $paymentMethods = PaymentMethod::all();

        $storeAddress = 'Jl. Jenderal Ahmad Yani, Tangga Takat, Kec. Seberang Ulu II, Kota Palembang, Sumatera Selatan 30265';
        $customerAddress = (string) (Auth::user()->home_address ?? '');
        $shippingPreview = $this->calculateShipping();

        // 4. Kirim semua variabel ke view select_payment
        return view('checkout.select_payment', compact('cartItems', 'paymentMethods', 'total', 'storeAddress', 'customerAddress', 'shippingPreview'));
    }

    public function shippingQuote(Request $request)
    {
        $data = $request->validate([
            'pickup_method' => 'required|in:delivery,pickup',
            'shipping_address' => 'nullable|string|max:500',
        ]);

        if ($data['pickup_method'] === 'pickup') {
            return response()->json([
                'success' => true,
                'distance_km' => null,
                'shipping_cost' => 0,
            ]);
        }

        $quote = $this->calculateShipping();

        return response()->json([
            'success' => true,
            'distance_km' => null,
            'shipping_cost' => $quote['shipping_cost'],
        ]);
    }

    public function placeOrder(Request $request)
    {
        try {
            $data = $request->validate([
                'payment_method_id' => 'required|exists:payment_methods,id',
                'pickup_method' => 'required|in:delivery,pickup',
                'shipping_address' => 'nullable|string|max:500',
            ]);

            $user = Auth::user();
            $cartItems = Keranjang::with(['produk', 'bundling'])->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
            }

            // Hitung Subtotal
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $harga = $item->bundling_id
                    ? $item->bundling->bundling_price
                    : ($item->harga_at_time ?? $item->produk->harga_jual_umum);
                $subtotal += $harga * $item->jumlah;
            }

            $shippingAddress = trim((string) ($data['shipping_address'] ?? ''));
            $shippingCost = 0;

            $order = DB::transaction(function () use ($data, $user, $cartItems, $subtotal, $shippingAddress, &$shippingCost) {
                if ($data['pickup_method'] === 'delivery') {
                    if ($shippingAddress === '') {
                        throw new \RuntimeException('Alamat pengiriman wajib diisi untuk delivery.');
                    }

                    $quote = $this->calculateShipping();
                    $shippingCost = $quote['shipping_cost'];
                }

                $order = Order::create([
                    'order_number' => 'TRM-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                    'user_id' => $user->id,
                    'total' => $subtotal + $shippingCost,
                    'payment_method_id' => $data['payment_method_id'],
                    'pickup_method' => $data['pickup_method'],
                    'shipping_address' => $data['pickup_method'] === 'delivery' ? $shippingAddress : null,
                    'shipping_distance_km' => null,
                    'shipping_cost' => $shippingCost,
                    'payment_status' => 'pending',
                    'order_status' => 'new',
                ]);

                foreach ($cartItems as $item) {
                    $harga = $item->bundling_id
                        ? $item->bundling->bundling_price
                        : ($item->harga_at_time ?? $item->produk->harga_jual_umum);

                    $order->items()->create([
                        'kd_produk' => $item->kd_produk,
                        'quantity' => $item->jumlah,
                        'price' => $harga,
                    ]);
                }

                $order->load('items.produk');
                $order->deductStockForCompletedOrder();

                return $order;
            });

            return redirect()->route('checkout.upload_proof', $order->id)->with('success', 'Pesanan berhasil dibuat. Silakan unggah bukti pembayaran.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', $e->getMessage() ?: 'Gagal membuat pesanan.');
        }
    }

    // HALAMAN UPLOAD BUKTI (Setelah buat order)
  
    public function uploadProof($orderId)
    {
        $order = Order::with(['paymentMethod', 'items'])->findOrFail($orderId);
        return view('checkout.upload_proof', compact('order'));
    }

    // PROSES SIMPAN BUKTI TRANSFER
    public function storeProof(Request $request, $orderId)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::findOrFail($orderId);
        $user = Auth::user();

        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('bukti_transfer', 'public');
            
            // Save to the `payment_proof` column which the Order model expects
            $order->update([
                'payment_proof' => $path,
                'status' => 'menunggu_verifikasi',
                'payment_status' => 'waiting_confirmation',
            ]);

            // BARU HAPUS KERANJANG setelah payment proof sukses diunggah
            Keranjang::where('user_id', $user->id)->delete();

            return redirect()->route('pesanan.show', $order->id)
                             ->with('success', 'Bukti transfer berhasil diunggah!');
        }

        return back()->with('error', 'Gagal mengunggah foto.');
    }

    // HALAMAN TUNGGU VERIFIKASI
    
    public function waiting($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('checkout.waiting', compact('order'));
    }

    public function calculateShipping(): array
    {
        $settings = \App\Models\ShippingSetting::first();
        $shippingCost = (int) (
            $settings->flat_rate
            ?? $settings->price_per_km
            ?? 15000
        );

        return [
            'distance_km' => null,
            'shipping_cost' => $shippingCost,
        ];
    }
}