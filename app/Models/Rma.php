<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rma extends Model
{
    use HasFactory;

    protected $fillable = [
        'rma_number',
        'user_id',
        'order_id',
        'guest_name',
        'guest_phone',
        'status',
        'resolution_type',
        'reason',
        'admin_notes',
    ];

    public function items()
    {
        return $this->hasMany(RmaItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}