<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerInteraction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'interaction_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
