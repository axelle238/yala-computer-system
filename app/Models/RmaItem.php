<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RmaItem extends Model
{
    protected $fillable = [
        'rma_id',
        'product_id',
        'quantity',
        'condition',
        'problem_description',
        'evidence_files', // New
    ];

    protected $casts = [
        'evidence_files' => 'array', // New
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function replacementProduct()
    {
        return $this->belongsTo(Product::class, 'replacement_product_id');
    }
}
