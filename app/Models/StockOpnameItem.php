<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameItem extends Model
{
    protected $fillable = [
        'stock_opname_id',
        'product_id',
        'system_stock',
        'physical_stock',
        'variance',
        'notes',
    ];

    public function produk()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product()
    {
        return $this->produk();
    }
}
