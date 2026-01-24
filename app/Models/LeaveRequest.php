<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class LeaveRequest extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'approved_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-amber-100 text-amber-600',
            'approved' => 'bg-emerald-100 text-emerald-600',
            'rejected' => 'bg-rose-100 text-rose-600',
            default => 'bg-slate-100',
        };
    }
}
