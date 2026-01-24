<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildLike extends Model
{
    protected $fillable = ['user_id', 'saved_build_id'];
}
