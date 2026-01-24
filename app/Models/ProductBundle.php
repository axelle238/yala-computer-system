<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBundle extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(ProductBundleItem::class);
    }
    
    // Virtual attributes
    public function getOriginalPriceAttribute()
    {
        return $this->items->sum(function($item) {
            return $item->product->sell_price * $item->quantity;
        });
    }
}