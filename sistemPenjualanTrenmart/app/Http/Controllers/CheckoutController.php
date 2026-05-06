<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::id();
        $cartItems = Keranjang::where('user_id', $user)->get();
        $paymentMethods = \App\Models\PaymentMethod::all(); 

        $total = 0;
        foreach ($cartItems as $item) {
            $harga = $item->bundling_id 
                    ? $item->bundling->bundling_price 
                    : ($item->produk->harga_jual_umum); 
            $total += $harga * $item->jumlah;
        }

        // Kirim variabel $total ke view
        return view('checkout.select_payment', compact('cartItems', 'paymentMethods', 'total'));
    }
    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'pickup_method' => 'nullable|string',
        ]);

        $items = Keranjang::with('produk')->where('user_id', Auth::id())->get();
        
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        // 1. Hitung Total (Sama seperti sebelumnya)
        $total = 0;
        foreach ($items as $item) {
            $harga = (Auth::user()->customer_type === 'langganan') 
                    ? ($item->produk->harga_jual_langganan ?? $item->produk->harga_jual_umum) 
                    : $item->produk->harga_jual_umum;
            $total += $harga * $item->jumlah;
        }

        // 2. Buat Order
        $order = Order::create([
            'order_number' => 'TRM-' . strtoupper(Str::random(8)),
            'user_id' => Auth::id(),
            'total' => $total,
            'payment_method_id' => $request->payment_method_id,
            'pickup_method' => $request->pickup_method ?? 'delivery',
            'payment_status' => 'pending', // Status masih pending
            'order_status' => 'new',
            'alamat_pengiriman' => Auth::user()->alamat,
        ]);

        // 3. Pindahkan item keranjang ke Order Items
        foreach ($items as $item) {
            $price_at_time = (Auth::user()->customer_type === 'langganan') 
                            ? ($item->produk->harga_jual_langganan ?? $item->produk->harga_jual_umum) 
                            : $item->produk->harga_jual_umum;
            
            OrderItem::create([
                'order_id' => $order->id,
                'kd_produk' => $item->kd_produk,
                'quantity' => $item->jumlah,
                'price' => $price_at_time,
            ]);
        }

        return redirect()->route('checkout.upload_proof', $order->id);
    }

    public function storeProof(Request $request, $orderId)
    {
        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('payment_proofs', 'public');

            $order->update([
                'payment_proof' => $path,
                'payment_status' => 'waiting_confirmation'
            ]);

            // HAPUS KERANJANG DI SINI
            // Sekarang keranjang hanya terhapus jika bukti sudah dikirim.
            Keranjang::where('user_id', Auth::id())->delete();

            return redirect()->route('checkout.waiting', $order->id)
                            ->with('success', 'Bukti transfer berhasil diunggah.');
        }

        return back()->with('error', 'Gagal mengunggah gambar.');
    }
}