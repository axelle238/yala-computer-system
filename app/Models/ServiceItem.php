<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    protected $fillable = [
        'service_ticket_id',
        'product_id',
        'item_name',
        'quantity',
        'cost',
        'price',
        'note',
        'is_stock_deducted',
        'serial_number',
        'warranty_duration',
    ];

    public function tiket()
    {
        return $this->belongsTo(ServiceTicket::class, 'service_ticket_id');
    }

    public function produk()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product()
    {
        return $this->produk();
    }
}
