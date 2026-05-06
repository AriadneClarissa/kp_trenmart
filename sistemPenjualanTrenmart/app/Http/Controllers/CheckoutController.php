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
    // Menampilkan halaman pilih bank
    public function index()
    {
        $items = Keranjang::with('produk')->where('user_id', Auth::id())->get();
        
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $total = 0;
        foreach ($items as $item) {
            $harga = (Auth::user()->customer_type === 'langganan') 
                    ? ($item->produk->harga_jual_langganan ?? $item->produk->harga_jual_umum) 
                    : $item->produk->harga_jual_umum;
            $item->harga_at_time = $harga;
            $total += $harga * $item->jumlah;
        }

        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        return view('checkout.select_payment', compact('items', 'total', 'paymentMethods'));
    }

    // Proses pembuatan Order (Tombol Lanjut ke Bukti Pembayaran)
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

        $total = 0;
        foreach ($items as $item) {
            $harga = (Auth::user()->customer_type === 'langganan') 
                    ? ($item->produk->harga_jual_langganan ?? $item->produk->harga_jual_umum) 
                    : $item->produk->harga_jual_umum;
            $total += $harga * $item->jumlah;
        }

        // Simpan ke Tabel Orders
        $order = Order::create([
            'order_number' => 'TRM-' . strtoupper(Str::random(8)),
            'user_id' => Auth::id(),
            'total' => $total,
            'payment_method_id' => $request->payment_method_id,
            'pickup_method' => $request->pickup_method ?? 'delivery',
            'payment_status' => 'pending',
            'order_status' => 'new',
            'alamat_pengiriman' => Auth::user()->alamat,
        ]);

        // Simpan ke Tabel Order Items
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

        // PENTING: Hapus keranjang HANYA jika order sukses dibuat
        Keranjang::where('user_id', Auth::id())->delete();

        return redirect()->route('checkout.upload_proof', $order->id);
    }

    // Menampilkan halaman instruksi transfer & upload foto
    public function uploadProof($orderId)
    {
        $order = Order::with('paymentMethod')
                      ->where('id', $orderId)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        return view('checkout.upload_proof', compact('order'));
    }
}