<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiveItem extends Model
{
    protected $fillable = [
        'goods_receive_id',
        'product_id',
        'qty_ordered_snapshot',
        'qty_received',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
