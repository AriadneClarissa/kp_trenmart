<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderMessage;
use App\Models\User;
use App\Notifications\OrderActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class OrderMessageController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $request->validate(['message' => 'required|string']);

        $order = Order::where('id', $orderId)->firstOrFail();

        // Ensure only participants (owner or admin) can post
        if (!Auth::user()->isAdmin() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        $msg = OrderMessage::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        $notificationTitle = 'Pesan baru pada pesanan #' . $order->order_number;
        $notificationBody = Str::limit($request->message, 100);
        $notificationUrl = Auth::user()->isAdmin()
            ? route('admin.orders.show', $order->id)
            : route('pesanan.show', $order->id);

        if (Auth::user()->isAdmin()) {
            $customer = $order->user()->first();

            if ($customer) {
                $customer->notify(new OrderActivityNotification(
                    title: $notificationTitle,
                    body: 'Admin membalas chat: ' . $notificationBody,
                    url: route('pesanan.show', $order->id),
                    type: 'chat',
                    orderNumber: $order->order_number,
                    actorName: Auth::user()->name,
                ));
            }
        } else {
            $admins = User::where('role', 'admin')->get();

            Notification::send($admins, new OrderActivityNotification(
                title: $notificationTitle,
                body: Auth::user()->name . ' mengirim chat: ' . $notificationBody,
                url: route('admin.orders.show', $order->id),
                type: 'chat',
                orderNumber: $order->order_number,
                actorName: Auth::user()->name,
            ));
        }

        return back();
    }
}
