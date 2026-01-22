<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Banner extends Model
{
    use LogsActivity;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'link_url',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}