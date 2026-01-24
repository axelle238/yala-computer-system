<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function usages()
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function scopeRedeemable($query)
    {
        return $query->where('is_active', true)
                     ->where('is_public', true)
                     ->where('points_cost', '>', 0)
                     ->where(function($q) {
                         $q->whereNull('end_date')
                           ->orWhere('end_date', '>=', now());
                     });
    }

    public function isValidForUser($userId, $totalSpend)
    {
        // 1. Active & Date Check
        if (!$this->is_active) return false;
        $now = now();
        if ($this->start_date && $now < $this->start_date) return false;
        if ($this->end_date && $now > $this->end_date) return false;

        // 2. Min Spend
        if ($totalSpend < $this->min_spend) return false;

        // 3. Global Quota
        if ($this->usage_limit !== null) {
            if ($this->usages()->count() >= $this->usage_limit) return false;
        }

        // 4. User Quota
        if ($userId) {
            $userUsage = $this->usages()->where('user_id', $userId)->count();
            if ($userUsage >= $this->usage_per_user) return false;
        }

        return true;
    }

    public function calculateDiscount($totalSpend)
    {
        $discount = 0;
        if ($this->type === 'fixed') {
            $discount = $this->amount;
        } else {
            $discount = $totalSpend * ($this->amount / 100);
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        }
        return min($discount, $totalSpend); // Cannot exceed total
    }
}
