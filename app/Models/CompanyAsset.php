<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyAsset extends Model
{
    protected $guarded = [];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function depreciations()
    {
        return $this->hasMany(AssetDepreciation::class);
    }

    public function calculateDepreciation()
    {
        // Straight-line method: (Cost - Salvage) / Life
        // Assuming 0 salvage value for simplicity
        $monthlyDepreciation = $this->purchase_price / ($this->useful_life_years * 12);
        
        // Logic to apply monthly:
        // Check if already depreciated this month
        $existing = $this->depreciations()
            ->whereMonth('depreciation_date', now()->month)
            ->whereYear('depreciation_date', now()->year)
            ->exists();

        if (!$existing && $this->current_value > 0) {
            $newValue = max(0, $this->current_value - $monthlyDepreciation);
            
            $this->depreciations()->create([
                'depreciation_date' => now(),
                'amount' => $monthlyDepreciation,
                'book_value_after' => $newValue
            ]);

            $this->update(['current_value' => $newValue]);
            
            // Record as Expense automatically
            Expense::create([
                'title' => "Depresiasi Aset: {$this->name} ({$this->asset_tag})",
                'amount' => $monthlyDepreciation,
                'expense_date' => now(),
                'category' => 'depreciation',
                'user_id' => auth()->id() ?? 1, // Fallback system
                'notes' => 'Auto-generated depreciation'
            ]);
        }
    }
}
