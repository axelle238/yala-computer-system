<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceive extends Model
{
    protected $guarded = [];

    protected $casts = [
        'received_date' => 'date',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function items()
    {
        return $this->hasMany(GoodsReceiveItem::class);
    }
}
