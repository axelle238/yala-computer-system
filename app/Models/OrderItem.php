<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Alias untuk pesanan (Backward Compatibility).
     */
    public function order()
    {
        return $this->pesanan();
    }

    public function produk()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Alias untuk produk (Backward Compatibility).
     */
    public function product()
    {
        return $this->produk();
    }
}
