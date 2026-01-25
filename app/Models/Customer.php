<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'phone', 'email', 'points', 'join_date'];

    protected $casts = [
        'join_date' => 'date',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'guest_whatsapp', 'phone');
    }
}
