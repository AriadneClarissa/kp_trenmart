<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Notifications\OrderActivityNotification;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'paymentMethod')->orderBy('created_at','desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.produk','paymentMethod','messages.user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function confirmPayment($id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => 'confirmed',
            'order_status' => 'processing'
        ]);

        if ($order->user) {
            $order->user->notify(new OrderActivityNotification(
                title: 'Pembayaran dikonfirmasi',
                body: 'Pembayaran pesanan #' . $order->order_number . ' sudah dikonfirmasi dan sedang diproses.',
                url: route('pesanan.show', $order->id),
                type: 'payment_confirmed',
                orderNumber: $order->order_number,
                actorName: auth()->user()->name ?? 'Admin',
            ));
        }

        return back()->with('success','Pembayaran telah dikonfirmasi.');
    }

    public function rejectPayment($id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => 'rejected',
            'order_status' => 'payment_rejected'
        ]);

        if ($order->user) {
            $order->user->notify(new OrderActivityNotification(
                title: 'Pembayaran ditolak',
                body: 'Pembayaran pesanan #' . $order->order_number . ' ditolak. Silakan unggah bukti ulang jika perlu.',
                url: route('pesanan.show', $order->id),
                type: 'payment_rejected',
                orderNumber: $order->order_number,
                actorName: auth()->user()->name ?? 'Admin',
            ));
        }

        return back()->with('success','Pembayaran telah ditolak.');
    }
}
