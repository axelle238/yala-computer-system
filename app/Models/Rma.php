<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Rma extends Model
{
    use LogsActivity;

    protected $guarded = [];

    // Konstanta Status untuk Konsistensi
    const STATUS_REQUESTED = 'requested';

    const STATUS_APPROVED = 'approved';

    const STATUS_RECEIVED = 'received';

    const STATUS_PROCESSING = 'processing';

    const STATUS_VENDOR = 'vendor_process';

    const STATUS_RESOLVED = 'resolved';

    const STATUS_REJECTED = 'rejected';

    const RESOLUTION_REPAIR = 'repair';

    const RESOLUTION_REPLACE = 'replacement';

    const RESOLUTION_REFUND = 'refund';

    public function item()
    {
        return $this->hasMany(RmaItem::class);
    }

    public function items()
    {
        return $this->item();
    }

    public function pelacakan()
    {
        return $this->hasMany(RmaTrack::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function pesanan()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getCustomerNameAttribute()
    {
        return $this->pengguna ? $this->pengguna->name : ($this->guest_name ?? 'Guest');
    }

    // Helper Status Label
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'requested' => 'Permintaan Baru',
            'approved' => 'Disetujui',
            'received' => 'Diterima Toko',
            'processing' => 'Diproses',
            'vendor_process' => 'Klaim Distributor',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak',
            default => 'Menunggu',
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'requested' => 'bg-slate-100 text-slate-600',
            'approved' => 'bg-blue-100 text-blue-600',
            'received' => 'bg-indigo-100 text-indigo-600',
            'processing' => 'bg-amber-100 text-amber-600',
            'vendor_process' => 'bg-purple-100 text-purple-600',
            'resolved' => 'bg-emerald-100 text-emerald-600',
            'rejected' => 'bg-rose-100 text-rose-600',
            default => 'bg-slate-100',
        };
    }
}
