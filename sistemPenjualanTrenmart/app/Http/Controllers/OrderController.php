<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.produk', 'paymentMethod', 'user')
                        ->where('user_id', Auth::id())
                        ->orderByDesc('created_at')
                        ->get();

        return view('pesanan.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.produk', 'paymentMethod', 'messages.user'])
                      ->where('id', $id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        $computedDistance = null;
        if ($order->shipping_distance_km === null && $order->shipping_address) {
            try {
                $checkout = app(\App\Http\Controllers\CheckoutController::class);
                $quote = $checkout->calculateShipping($checkout->getStoreAddress(), $order->shipping_address);
                $computedDistance = $quote['distance_km'] ?? null;

                // Persist computed distance and shipping cost if missing
                if ($computedDistance !== null) {
                    $order->update([
                        'shipping_distance_km' => $quote['distance_km'],
                        'shipping_cost' => $quote['shipping_cost'] ?? $order->shipping_cost,
                    ]);
                }
            } catch (\Throwable $e) {
                report($e);
                $computedDistance = null;
            }
        }

        return view('pesanan.show', compact('order', 'computedDistance'));
    }

    public function markAsCompleted(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->payment_status !== 'confirmed') {
            return back()->with('error', 'Pesanan belum bisa diselesaikan sebelum pembayaran dikonfirmasi.');
        }

        if (! in_array($order->order_status, ['ready_to_ship', 'processing'], true)) {
            return back()->with('error', 'Pesanan hanya bisa ditandai selesai saat sudah diterima pelanggan.');
        }

        try {
            $order->deductStockForCompletedOrder();
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', $e->getMessage() ?: 'Gagal mengurangi stok produk.');
        }

        $order->update([
            'order_status' => 'completed',
            'completed_at' => now(),
        ]);

        try {
            ActivityLog::create([
                'actor_id' => Auth::id(),
                'action' => 'customer_mark_completed',
                'details' => 'Pelanggan menandai pesanan selesai (#' . $order->order_number . ')',
                'ip_address' => $request->ip(),
                'subject_type' => 'order',
                'subject_id' => $order->id,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('success', 'Pesanan berhasil ditandai selesai.');
    }
}
