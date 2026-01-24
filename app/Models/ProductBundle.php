<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBundle extends Model
{
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(ProductBundleItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_bundle_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    // Dynamic stock calculation based on components
    public function getStockAttribute()
    {
        $minStock = 999999;
        
        foreach ($this->items as $item) {
            $componentStock = floor($item->product->stock_quantity / $item->quantity);
            if ($componentStock < $minStock) {
                $minStock = $componentStock;
            }
        }

        return $minStock === 999999 ? 0 : $minStock;
    }
}
