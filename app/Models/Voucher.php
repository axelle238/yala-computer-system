<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function isValidForUser($userId, $cartTotal)
    {
        if (!$this->is_active) return false;
        if (now()->lt($this->start_date) || now()->gt($this->end_date)) return false;
        if ($this->usage_limit > 0 && $this->used_count >= $this->usage_limit) return false;
        if ($cartTotal < $this->min_spend) return false;

        return true;
    }

    public function calculateDiscount($total)
    {
        if ($this->type === 'fixed') {
            return min($this->discount_value, $total);
        } elseif ($this->type === 'percent') {
            $discount = $total * ($this->discount_value / 100);
            if ($this->max_discount_amount > 0) {
                $discount = min($discount, $this->max_discount_amount);
            }
            return $discount;
        }
        return 0;
    }
}
