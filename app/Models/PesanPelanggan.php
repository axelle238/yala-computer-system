<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model PesanPelanggan
 *
 * Digunakan untuk menyimpan pesan yang dikirim oleh pelanggan melalui formulir kontak.
 */
class PesanPelanggan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'pesan_pelanggan';

    /**
     * Atribut yang dapat diisi massal.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'surel',
        'subjek',
        'isi_pesan',
        'status', // baru, dibaca, dibalas
    ];

    /**
     * Konstanta untuk status pesan.
     */
    const STATUS_BARU = 'baru';

    const STATUS_DIBACA = 'dibaca';

    const STATUS_DIBALAS = 'dibalas';
}
