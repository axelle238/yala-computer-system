<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model PesanObrolan
 *
 * Merepresentasikan satu pesan dalam sesi obrolan.
 */
class PesanObrolan extends Model
{
    /**
     * Nama tabel.
     */
    protected $table = 'pesan_obrolan';

    /**
     * Atribut yang dapat diisi massal.
     */
    protected $fillable = [
        'id_sesi',
        'id_pengguna', // Null jika tamu, atau ID Admin/Pelanggan jika login
        'is_balasan_admin',
        'isi',
        'lampiran',
        'is_dibaca',
    ];

    /**
     * Casting atribut.
     */
    protected $casts = [
        'is_balasan_admin' => 'boolean',
        'is_dibaca' => 'boolean',
    ];

    /**
     * Relasi ke Sesi Obrolan.
     */
    public function sesi(): BelongsTo
    {
        return $this->belongsTo(SesiObrolan::class, 'id_sesi');
    }

    /**
     * Relasi ke Pengirim (User).
     */
    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}
