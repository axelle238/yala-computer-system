<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingManifest extends Model
{
    protected $guarded = [];

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
