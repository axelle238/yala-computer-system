<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'period_month',
        'pay_date',
        'base_salary',
        'total_commission',
        'deductions',
        'net_salary',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}