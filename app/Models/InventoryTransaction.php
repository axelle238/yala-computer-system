<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class InventoryTransaction
 * 
 * Records every movement of stock to ensure full traceability.
 * Never delete these records; they are the audit trail.
 * 
 * @package App\Models
 */
class InventoryTransaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'remaining_stock',
        'reference_number',
        'notes',
    ];

    /**
     * Get the product associated with the transaction.
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who performed the transaction.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}