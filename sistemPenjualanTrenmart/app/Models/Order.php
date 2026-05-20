<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = ['order_number','user_id','total','payment_method_id','pickup_method','shipping_address','shipping_distance_km','shipping_cost','payment_status','order_status','payment_proof','stock_deducted_at','completed_at'];

    protected $casts = [
        'stock_deducted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function messages()
    {
        return $this->hasMany(OrderMessage::class)->orderBy('created_at','asc');
    }

    public function deductStockForCompletedOrder(): bool
    {
        if ($this->stock_deducted_at) {
            return false;
        }

        return DB::transaction(function () {
            $order = self::whereKey($this->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->stock_deducted_at) {
                return false;
            }

            $order->load('items.produk');

            foreach ($order->items as $item) {
                $produk = $item->produk()->lockForUpdate()->first();

                if (! $produk) {
                    throw new \RuntimeException('Produk untuk item pesanan #' . $order->order_number . ' tidak ditemukan.');
                }

                if ((int) $produk->stok_tersedia < (int) $item->quantity) {
                    throw new \RuntimeException('Stok produk ' . $produk->nama_produk . ' tidak mencukupi untuk pesanan #' . $order->order_number . '.');
                }

                $produk->decrement('stok_tersedia', (int) $item->quantity);
            }

            $order->update([
                'stock_deducted_at' => now(),
            ]);

            return true;
        });
    }
}
