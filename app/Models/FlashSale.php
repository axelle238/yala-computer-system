<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class FlashSale extends Model
{
    use LogsActivity;

    protected $fillable = [
        'product_id', 'discount_price', 'start_time', 'end_time', 'quota', 'is_active'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper untuk cek apakah sedang berlangsung
    public function isRunning()
    {
        return $this->is_active && 
               now()->between($this->start_time, $this->end_time) && 
               $this->quota > 0;
    }
}