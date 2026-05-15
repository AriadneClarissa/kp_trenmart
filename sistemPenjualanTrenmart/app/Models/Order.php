<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_number','user_id','total','payment_method_id','pickup_method','shipping_address','shipping_distance_km','shipping_cost','payment_status','order_status','payment_proof'];

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
}
