<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOffer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'expected_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getConditionLabelAttribute()
    {
        return match ($this->condition) {
            'new' => 'Baru (Segel)',
            'used_like_new' => 'Bekas (Seperti Baru)',
            'used_good' => 'Bekas (Layak Pakai)',
            'used_fair' => 'Bekas (Perlu Perbaikan)',
            default => $this->condition,
        };
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Menunggu Review',
            'reviewed' => 'Sedang Direview',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-slate-100 text-slate-600',
            'reviewed' => 'bg-blue-100 text-blue-600',
            'accepted' => 'bg-emerald-100 text-emerald-600',
            'rejected' => 'bg-rose-100 text-rose-600',
            default => 'bg-slate-100',
        };
    }
}
