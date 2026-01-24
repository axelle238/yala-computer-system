<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_whatsapp',
        'order_number',
        'total_amount',
        'status',
        'payment_status',
        'notes',
        'shipping_address',
        'shipping_city',
        'shipping_courier',
        'shipping_cost',
        'shipping_tracking_number', // New
        'shipped_at', // New
        'points_redeemed',
        'discount_amount',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
