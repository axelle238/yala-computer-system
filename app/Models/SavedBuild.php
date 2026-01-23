<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedBuild extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'total_price_estimated',
        'components',
        'share_token',
    ];

    protected $casts = [
        'components' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}