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
    ];

    public function ticket()
    {
        return $this->belongsTo(ServiceTicket::class, 'service_ticket_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
