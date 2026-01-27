<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBundle extends Model
{
    protected $guarded = [];

    public function item()
    {
        return $this->hasMany(ProductBundleItem::class);
    }

    public function items()
    {
        return $this->item();
    }

    public function produk()
    {
        return $this->belongsToMany(Product::class, 'product_bundle_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function products()
    {
        return $this->produk();
    }

    // Kalkulasi stok dinamis berdasarkan komponen
    public function getStockAttribute()
    {
        $minStock = 999999;

        foreach ($this->item as $item) {
            $componentStock = floor($item->product->stock_quantity / $item->quantity);
            if ($componentStock < $minStock) {
                $minStock = $componentStock;
            }
        }

        return $minStock === 999999 ? 0 : $minStock;
    }
}
