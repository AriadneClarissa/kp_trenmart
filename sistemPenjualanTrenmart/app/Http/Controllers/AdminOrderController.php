<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function confirmPayment(Request $request, $id)
    {
        abort_unless(Auth::check() && (Auth::user()->isAdmin() || (method_exists(Auth::user(), 'isCashier') && Auth::user()->isCashier())), 403);
        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => 'confirmed',
            'order_status' => 'processing'
        ]);

        $this->logOrderAction($order, 'confirm_payment', 'Pembayaran dikonfirmasi dan status pesanan diubah menjadi diproses', $request);

        if ($order->user) {
            $order->user->notify(new OrderActivityNotification(
                title: 'Pembayaran dikonfirmasi',
                body: 'Pembayaran pesanan #' . $order->order_number . ' sudah dikonfirmasi dan sedang diproses.',
                url: route('pesanan.show', $order->id),
                type: 'payment_confirmed',
                orderNumber: $order->order_number,
                actorName: Auth::user()->name ?? 'Admin',
            ));
        }

        return back()->with('success','Pembayaran telah dikonfirmasi.');
    }

    public function rejectPayment(Request $request, $id)
    {
        abort_unless(Auth::check() && (Auth::user()->isAdmin() || (method_exists(Auth::user(), 'isCashier') && Auth::user()->isCashier())), 403);
        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => 'rejected',
            'order_status' => 'payment_rejected'
        ]);

        $this->logOrderAction($order, 'reject_payment', 'Pembayaran ditolak', $request);

        if ($order->user) {
            $order->user->notify(new OrderActivityNotification(
                title: 'Pembayaran ditolak',
                body: 'Pembayaran pesanan #' . $order->order_number . ' ditolak. Silakan unggah bukti ulang jika perlu.',
                url: route('pesanan.show', $order->id),
                type: 'payment_rejected',
                orderNumber: $order->order_number,
                actorName: Auth::user()->name ?? 'Admin',
            ));
        }

        return back()->with('success','Pembayaran telah ditolak.');
    }

    public function updateStatus(Request $request, $id)
    {
        abort_unless(Auth::check() && (Auth::user()->isAdmin() || (method_exists(Auth::user(), 'isCashier') && Auth::user()->isCashier())), 403);
        $data = $request->validate([
            'order_status' => 'required|in:processing,ready_to_ship,completed',
        ]);

        $order = Order::findOrFail($id);

        if ($order->payment_status !== 'confirmed') {
            return back()->with('error', 'Pesanan hanya bisa diproses setelah pembayaran dikonfirmasi.');
        }

        if ($data['order_status'] === 'completed') {
            try {
                $order->deductStockForCompletedOrder();
            } catch (\Throwable $e) {
                report($e);
                return back()->with('error', $e->getMessage() ?: 'Gagal mengurangi stok produk.');
            }
        }

        $order->update([
            'order_status' => $data['order_status'],
            'completed_at' => $data['order_status'] === 'completed' ? now() : $order->completed_at,
        ]);

        $statusLabel = [
            'processing' => 'diproses',
            'ready_to_ship' => 'siap dikirim',
            'completed' => 'selesai diproses',
        ][$data['order_status']] ?? $data['order_status'];

        $this->logOrderAction($order, 'update_order_status', 'Status pesanan diubah menjadi ' . $statusLabel, $request);

        if ($order->user) {
            $order->user->notify(new OrderActivityNotification(
                title: 'Status pesanan diperbarui',
                body: 'Status pesanan #' . $order->order_number . ' sekarang ' . $statusLabel . '.',
                url: route('pesanan.show', $order->id),
                type: 'order_status_updated',
                orderNumber: $order->order_number,
                actorName: Auth::user()->name ?? 'Admin',
            ));
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    private function logOrderAction(Order $order, string $action, string $details, Request $request): void
    {
        try {
            ActivityLog::create([
                'actor_id' => Auth::id(),
                'action' => $action,
                'details' => $details . ' (#' . $order->order_number . ')',
                'ip_address' => $request->ip(),
                'subject_type' => 'order',
                'subject_id' => $order->id,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
