<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDiscussion extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'message',
        'parent_id',
        'is_admin_reply',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function replies()
    {
        return $this->hasMany(ProductDiscussion::class, 'parent_id');
    }
}
