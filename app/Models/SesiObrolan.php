<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Model SesiObrolan
 *
 * Merepresentasikan satu sesi percakapan antara pelanggan dan admin/CS.
 */
class SesiObrolan extends Model
{
    /**
     * Nama tabel.
     */
    protected $table = 'sesi_obrolan';

    /**
     * Atribut yang dapat diisi massal.
     */
    protected $fillable = ['id_pelanggan', 'token_tamu', 'topik', 'is_selesai'];

    /**
     * Casting atribut.
     */
    protected $casts = [
        'is_selesai' => 'boolean',
    ];

    /**
     * Relasi ke Pesan Obrolan.
     */
    public function pesan(): HasMany
    {
        return $this->hasMany(PesanObrolan::class, 'id_sesi');
    }

    /**
     * Relasi ke Pelanggan (User).
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pelanggan');
    }

    /**
     * Mendapatkan pesan terakhir untuk preview.
     */
    public function pesanTerakhir(): HasOne
    {
        return $this->hasOne(PesanObrolan::class, 'id_sesi')->latestOfMany();
    }
}
