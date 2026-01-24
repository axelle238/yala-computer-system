<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    protected $fillable = [
        'cash_register_id',
        'transaction_number',
        'type', // in, out
        'category', // 'service_payment', 'sales', 'expense', 'refund'
        'amount',
        'description',
        'reference_id',
        'reference_type',
        'created_by'
    ];

    public function register()
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
