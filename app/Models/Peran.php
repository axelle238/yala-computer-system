<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Peran
 * 
 * Mengelola peran pengguna dan daftar hak akses dalam format JSON.
 */
class Peran extends Model
{
    use HasFactory;

    /**
     * Nama tabel.
     *
     * @var string
     */
    protected $table = 'peran';

    /**
     * Atribut yang dapat diisi massal.
     *
     * @var array
     */
    protected $fillable = ['nama', 'hak_akses'];

    /**
     * Casting atribut.
     *
     * @var array
     */
    protected $casts = [
        'hak_akses' => 'array',
    ];

    /**
     * Relasi ke Pengguna (User).
     */
    public function pengguna(): HasMany
    {
        return $this->hasMany(User::class, 'id_peran');
    }

    /**
     * Mengecek apakah peran ini memiliki hak akses tertentu.
     * 
     * @param string $kodeAkses
     * @return bool
     */
    public function punyaAkses(string $kodeAkses): bool
    {
        return in_array($kodeAkses, $this->hak_akses ?? []);
    }
}
