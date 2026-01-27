<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'valid_until' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    /**
     * Alias plural
     */
    public function items(): HasMany
    {
        return $this->item();
    }

    public function pesananTerkonversi(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'converted_order_id');
    }
}
