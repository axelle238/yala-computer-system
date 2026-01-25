<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'opened_at',
        'closed_at',
        'opening_cash',
        'closing_cash',
        'expected_cash',
        'variance',
        'status', // open, closed
        'note',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_cash' => 'decimal:2',
        'closing_cash' => 'decimal:2',
        'expected_cash' => 'decimal:2',
        'variance' => 'decimal:2',
    ];

    public function transactions()
    {
        return $this->hasMany(CashTransaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSystemBalanceAttribute()
    {
        return $this->opening_cash + $this->transactions()->where('type', 'in')->sum('amount') - $this->transactions()->where('type', 'out')->sum('amount');
    }
}
