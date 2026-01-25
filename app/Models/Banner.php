<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

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
