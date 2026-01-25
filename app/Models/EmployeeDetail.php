<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDetail extends Model
{
    protected $guarded = [];

    protected $casts = [
        'join_date' => 'date',
        'date_of_birth' => 'date',
        'base_salary' => 'decimal:2',
        'allowance_daily' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
