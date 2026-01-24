<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SavedBuild extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'total_price_estimated',
        'components',
        'share_token',
        'is_public',
        'likes_count',
        'views_count',
    ];

    protected $casts = [
        'components' => 'array',
        'is_public' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(BuildLike::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BuildComment::class);
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
