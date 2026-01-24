<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceTicketPart extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productSerial()
    {
        return $this->belongsTo(ProductSerial::class);
    }
}