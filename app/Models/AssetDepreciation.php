<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetDepreciation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'depreciation_date' => 'date',
        'amount' => 'decimal:2',
        'book_value_after' => 'decimal:2',
    ];

    public function asset()
    {
        return $this->belongsTo(CompanyAsset::class, 'company_asset_id');
    }
}