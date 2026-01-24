<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_whatsapp',
        'order_number',
        'total_amount',
        'status',
        'payment_status', // unpaid, partial, paid
        'notes',
        'shipping_address',
        'shipping_city',
        'shipping_courier',
        'shipping_cost',
        'shipping_tracking_number',
        'shipped_at',
        'points_redeemed',
        'discount_amount',
        'due_date', // Added in previous migration for B2B
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function getPaidAmountAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }
}