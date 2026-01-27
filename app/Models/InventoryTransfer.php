<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryTransfer extends Model
{
    protected $fillable = [
        'transfer_number',
        'source_warehouse_id',
        'destination_warehouse_id',
        'requested_by',
        'approved_by',
        'status',
        'notes',
    ];

    public function gudangAsal(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'source_warehouse_id');
    }

    public function gudangTujuan(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'destination_warehouse_id');
    }

    public function pengaju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function penyetuju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function item(): HasMany
    {
        return $this->hasMany(InventoryTransferItem::class);
    }

    public function items(): HasMany
    {
        return $this->item();
    }
}
