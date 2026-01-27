<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcAssembly extends Model
{
    protected $guarded = [];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
