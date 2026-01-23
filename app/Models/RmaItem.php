<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'rma_id',
        'product_id',
        'serial_number',
        'quantity',
        'condition',
        'problem_description',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}