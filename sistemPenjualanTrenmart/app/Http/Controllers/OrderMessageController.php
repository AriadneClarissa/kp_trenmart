<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderMessage;
use App\Models\User;
use App\Notifications\OrderActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $recipients = collect();

        $customer = $order->user()->first();
        if ($customer && $customer->id !== Auth::id()) {
            $recipients->push($customer);
        }

        $staffRecipients = User::whereIn('role', ['owner', 'admin', 'kasir'])
            ->where('id', '!=', Auth::id())
            ->get();

        $recipients = $recipients->merge($staffRecipients)->unique('id')->values();

        $actorName = Auth::user()->name ?? 'User';
        $bodyPrefix = Auth::user()->isAdmin() || Auth::user()->isCashier() || Auth::user()->isOwner()
            ? 'Balasan chat dari ' . $actorName . ': '
            : $actorName . ' mengirim chat: ';

        foreach ($recipients as $recipient) {
            $recipient->notify(new OrderActivityNotification(
                title: $notificationTitle,
                body: $bodyPrefix . $notificationBody,
                url: $recipient->isAdmin() ? route('admin.orders.show', $order->id) : route('pesanan.show', $order->id),
                type: 'chat',
                orderNumber: $order->order_number,
                actorName: $actorName,
            ));
        }

        return back();
    }
}
