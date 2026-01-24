<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $fillable = [
        'opname_number',
        'warehouse_id',
        'creator_id',
        'approver_id',
        'status',
        'opname_date',
        'notes',
    ];

    protected $casts = [
        'opname_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function items()
    {
        return $this->hasMany(StockOpnameItem::class);
    }
}
