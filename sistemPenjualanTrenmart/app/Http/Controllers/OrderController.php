<?php

namespace App\Http\Controllers;

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

        return view('pesanan.show', compact('order'));
    }
}
