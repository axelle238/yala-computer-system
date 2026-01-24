<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RmaItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'evidence_files' => 'array',
    ];

    public function rma()
    {
        return $this->belongsTo(Rma::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}