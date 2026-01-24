<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'tracking_history' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function manifest()
    {
        return $this->belongsTo(ShippingManifest::class, 'shipping_manifest_id');
    }
}
