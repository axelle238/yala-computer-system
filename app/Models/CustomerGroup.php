<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    protected $guarded = [];

    public function customers()
    {
        return $this->hasMany(User::class);
    }
}
