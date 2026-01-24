<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'timestamp', // Time types are tricky in Laravel sometimes, string/carbon is better
        'clock_out' => 'timestamp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper: Hitung durasi kerja (jam)
    public function getWorkHoursAttribute()
    {
        if ($this->clock_in && $this->clock_out) {
            $in = \Carbon\Carbon::parse($this->clock_in);
            $out = \Carbon\Carbon::parse($this->clock_out);
            return $in->diffInHours($out);
        }
        return 0;
    }
}
