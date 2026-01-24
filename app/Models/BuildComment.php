<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildComment extends Model
{
    protected $fillable = ['user_id', 'saved_build_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
