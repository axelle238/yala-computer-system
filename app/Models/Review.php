<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'reviewer_name',
        'rating',
        'comment',
        'images', // New
        'is_approved',
        'reply',
        'replied_at',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'images' => 'array', // New
        'replied_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
