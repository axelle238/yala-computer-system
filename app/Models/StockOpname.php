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
        'status', // draft, counting, review, completed, cancelled
        'opname_date',
        'notes',
    ];

    protected $casts = [
        'opname_date' => 'date',
    ];

    public function item()
    {
        return $this->hasMany(StockOpnameItem::class);
    }

    public function items()
    {
        return $this->item();
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
