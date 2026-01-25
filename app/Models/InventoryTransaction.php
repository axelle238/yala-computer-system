<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class InventoryTransaction
 *
 * Records every movement of stock to ensure full traceability.
 * Never delete these records; they are the audit trail.
 */
class InventoryTransaction extends Model
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'user_id',
        'warehouse_id', // New
        'type',
        'quantity',
        'unit_price', // New: Snapshot of Sell Price
        'cogs',       // New: Snapshot of Buy Price (HPP)
        'serial_numbers',
        'remaining_stock',
        'reference_number',
        'notes',
    ];

    /**
     * Get the product associated with the transaction.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who performed the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
