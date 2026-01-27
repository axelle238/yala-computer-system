<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBundleItem extends Model
{
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Product::class);
    }
}
