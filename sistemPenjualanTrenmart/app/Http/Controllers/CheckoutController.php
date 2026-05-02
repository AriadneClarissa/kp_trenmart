<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = Keranjang::with('produk')->where('user_id', Auth::id())->get();

        $total = 0;
        foreach ($items as $item) {
            $harga = (Auth::user()->customer_type === 'langganan') 
                      ? ($item->produk->harga_jual_langganan ?? $item->produk->harga_jual_umum) 
                      : $item->produk->harga_jual_umum;
            $item->harga_at_time = $harga;
            $total += $harga * $item->jumlah;
        }

        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        $pickupOptions = [
            'delivery' => 'Delivery',
            'store_pickup' => 'Ambil di Toko',
        ];

        return view('checkout.select_payment', compact('items','total','paymentMethods','pickupOptions'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'pickup_method' => 'required|string',
        ]);

        $items = Keranjang::with('produk')->where('user_id', Auth::id())->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error','Keranjang kosong');
        }

        $total = 0;
        foreach ($items as $item) {
            $harga = (Auth::user()->customer_type === 'langganan') 
                      ? ($item->produk->harga_jual_langganan ?? $item->produk->harga_jual_umum) 
                      : $item->produk->harga_jual_umum;
            $total += $harga * $item->jumlah;
        }

        $order = Order::create([
            'order_number' => strtoupper(Str::random(8)),
            'user_id' => Auth::id(),
            'total' => $total,
            'payment_method_id' => $request->payment_method_id,
            'pickup_method' => $request->pickup_method,
            'payment_status' => 'pending',
            'order_status' => 'new',
        ]);

        foreach ($items as $item) {
            $price = (Auth::user()->customer_type === 'langganan') 
                      ? ($item->produk->harga_jual_langganan ?? $item->produk->harga_jual_umum) 
                      : $item->produk->harga_jual_umum;
            OrderItem::create([
                'order_id' => $order->id,
                'kd_produk' => $item->kd_produk,
                'quantity' => $item->jumlah,
                'price' => $price,
            ]);
        }

        // Hapus keranjang
        Keranjang::where('user_id', Auth::id())->delete();

        return redirect()->route('checkout.upload_proof', $order->id)->with('success','Order dibuat. Silakan unggah bukti transfer.');
    }

    public function uploadProof($orderId)
    {
        $order = Order::with('paymentMethod')->where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();
        return view('checkout.upload_proof', compact('order'));
    }

    public function storeProof(Request $request, $orderId)
    {
        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'payment_proof' => 'required|image|max:4096',
        ]);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $order->update([
            'payment_proof' => $path,
            'payment_status' => 'waiting_confirmation'
        ]);

        return redirect()->route('checkout.waiting', $order->id)->with('success','Bukti dikirim. Menunggu konfirmasi.');
    }

    public function waiting($orderId)
    {
        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();
        return view('checkout.waiting', compact('order'));
    }
}
