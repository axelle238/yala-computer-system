<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSerial extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Status accessor
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'available' => 'Tersedia',
            'sold' => 'Terjual',
            'rma' => 'Sedang RMA',
            'returned_to_supplier' => 'Retur ke Supplier',
            'broken' => 'Rusak/Musnah',
            default => 'Unknown'
        };
    }
}
