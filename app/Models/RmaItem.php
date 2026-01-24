<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RmaItem extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function replacementProduct()
    {
        return $this->belongsTo(Product::class, 'replacement_product_id');
    }
}
