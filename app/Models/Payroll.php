<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $guarded = [];

    protected $casts = [
        'pay_date' => 'date',
        'details' => 'array',
        'base_salary' => 'decimal:2',
        'total_allowance' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'total_commission' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
