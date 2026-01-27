<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Progres Servis
 */
class ProgresServis extends Model
{
    protected $table = 'progres_servis';

    protected $fillable = [
        'id_tiket_servis',
        'status',
        'deskripsi',
        'id_teknisi',
        'is_publik',
        'lampiran',
    ];

    protected $casts = [
        'lampiran' => 'array',
        'is_publik' => 'boolean',
    ];

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_teknisi');
    }

    /**
     * Alias untuk teknisi (Backward Compatibility).
     */
    public function technician(): BelongsTo
    {
        return $this->teknisi();
    }

    public function tiket(): BelongsTo
    {
        return $this->belongsTo(ServiceTicket::class, 'id_tiket_servis');
    }
}
