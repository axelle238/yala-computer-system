<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class GoodsReceive extends Model
{
    use LogsActivity;

    protected $fillable = [
        'purchase_order_id',
        'received_by',
        'grn_number',
        'supplier_do_number',
        'received_date',
        'notes',
        'status', // draft / finalized
        'warehouse_id' // Pastikan kolom ini ada, atau gunakan default logic
    ];

    protected $casts = [
        'received_date' => 'date',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function items()
    {
        return $this->hasMany(GoodsReceiveItem::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}