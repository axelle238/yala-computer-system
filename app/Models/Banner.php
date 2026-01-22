<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Banner extends Model
{
    use LogsActivity;

    protected $fillable = [
        'title',
        'image_path',
        'link_url',
        'is_active',
        'order',
    ];
}