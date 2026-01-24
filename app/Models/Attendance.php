<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shift_id',
        'date',
        'clock_in',
        'clock_out',
        'status', // present, late, absent, leave
        'late_minutes',
        'overtime_hours',
        'latitude',
        'longitude',
        'photo_path',
        'notes',
        'ip_address'
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function getStatusLabelAttribute()
    {
        if ($this->status === 'late') {
            return "Terlambat {$this->late_minutes}m";
        }
        return ucfirst($this->status);
    }
    
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'present' => 'text-emerald-600 bg-emerald-100',
            'late' => 'text-amber-600 bg-amber-100',
            'absent' => 'text-rose-600 bg-rose-100',
            'leave' => 'text-blue-600 bg-blue-100',
            default => 'text-slate-600 bg-slate-100',
        };
    }
}
