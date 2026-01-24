<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherUsage extends Model
{
    protected $guarded = [];
    public $timestamps = false; // Custom timestamps if needed, but migration has default timestamps? No, migration has $table->timestamp('used_at') but not $table->timestamps(). Wait, let's check migration again.
    // Migration: $table->timestamp('used_at'); NO standard timestamps.
    
    protected $casts = [
        'used_at' => 'datetime',
    ];
}
