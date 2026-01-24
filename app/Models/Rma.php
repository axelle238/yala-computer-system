<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rma extends Model
{
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(RmaItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
