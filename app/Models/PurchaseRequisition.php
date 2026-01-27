<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseRequisition extends Model
{
    protected $fillable = [
        'pr_number',
        'requested_by',
        'required_date',
        'status',
        'notes',
        'approved_by',
    ];

    protected $casts = [
        'required_date' => 'date',
    ];

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
        return $this->hasMany(PurchaseRequisitionItem::class);
    }
}
