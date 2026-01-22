<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Customer extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'phone', 'email', 'points', 'join_date'];

    protected $casts = [
        'join_date' => 'date',
    ];
}