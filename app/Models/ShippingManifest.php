<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingManifest extends Model
{
    protected $guarded = [];

    protected $casts = [
        'pickup_time' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}