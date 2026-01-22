<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Review extends Model
{
    use LogsActivity;

    protected $fillable = ['product_id', 'reviewer_name', 'rating', 'comment', 'is_approved'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}