<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class ServiceTicket extends Model
{
    use LogsActivity;

    protected $fillable = [
        'ticket_number',
        'customer_name',
        'customer_phone',
        'user_id', // Member ID
        'device_name',
        'serial_number_in',
        'passcode',
        'completeness',
        'problem_description',
        'warranty_type',
        'status',
        'estimated_cost',
        'final_cost',
        'technician_notes',
        'technician_id',
        'estimated_completion',
    ];

    protected $casts = [
        'estimated_completion' => 'datetime',
        'completeness' => 'array',
    ];

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function customerMember()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // New V2 Relationships
    public function parts()
    {
        return $this->hasMany(ServiceTicketPart::class);
    }

    public function progressLogs()
    {
        return $this->hasMany(ServiceTicketProgress::class)->latest();
    }

    // Keep backward compatibility if needed, but V2 uses progressLogs
    public function items()
    {
        return $this->hasMany(ServiceItem::class);
    }

    // Helper untuk label status warna-warni
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Menunggu Antrian',
            'diagnosing' => 'Pengecekan',
            'waiting_part' => 'Menunggu Sparepart',
            'repairing' => 'Sedang Diperbaiki',
            'ready' => 'Siap Diambil',
            'picked_up' => 'Selesai/Diambil',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-slate-100 text-slate-600',
            'diagnosing' => 'bg-blue-100 text-blue-600',
            'waiting_part' => 'bg-amber-100 text-amber-600',
            'repairing' => 'bg-indigo-100 text-indigo-600',
            'ready' => 'bg-emerald-100 text-emerald-600',
            'picked_up' => 'bg-emerald-500 text-white',
            'cancelled' => 'bg-rose-100 text-rose-600',
            default => 'bg-slate-100 text-slate-600',
        };
    }
}