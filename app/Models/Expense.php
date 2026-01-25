<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use LogsActivity;

    protected $fillable = [
        'title', 'amount', 'expense_date', 'category', 'notes', 'user_id',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
