<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'name', 
        'start_time', 
        'end_time', 
        'late_tolerance', 
        'work_days', 
        'is_active'
    ];

    protected $casts = [
        'work_days' => 'array',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}