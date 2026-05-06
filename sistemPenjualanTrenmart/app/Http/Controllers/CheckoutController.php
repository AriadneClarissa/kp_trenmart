<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;

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

        // 4. Kirim semua variabel ke view select_payment
        return view('checkout.select_payment', compact('cartItems', 'paymentMethods', 'total'));
    }

    // HALAMAN UPLOAD BUKTI (Setelah buat order)
  
    public function uploadProof($orderId)
    {
        $order = Order::with('paymentMethod')->findOrFail($orderId);
        return view('checkout.upload_proof', compact('order'));
    }

    // PROSES SIMPAN BUKTI TRANSFER
    public function storeProof(Request $request, $orderId)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::findOrFail($orderId);

        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('bukti_transfer', 'public');
            
            $order->update([
                'bukti_pembayaran' => $path,
                'status' => 'menunggu_verifikasi'
            ]);

            return redirect()->route('checkout.waiting', $order->id)
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
}