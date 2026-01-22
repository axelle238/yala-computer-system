<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class PurchaseOrder extends Model
{
    use LogsActivity;

    protected $fillable = [
        'po_number', 'supplier_id', 'status', 'order_date', 'total_amount', 'notes', 'created_by'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'total_amount' => 'float',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}