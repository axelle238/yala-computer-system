<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model SukuCadangServis
 *
 * Mencatat pemakaian barang/sparepart untuk sebuah tiket servis.
 */
class SukuCadangServis extends Model
{
    /**
     * Nama tabel.
     */
    protected $table = 'suku_cadang_servis';

    /**
     * Atribut yang dapat diisi massal.
     */
    protected $fillable = [
        'id_tiket_servis',
        'id_produk',
        'jumlah',
        'harga_satuan',
        'catatan',
    ];

    /**
     * Relasi ke Tiket Servis.
     */
    public function tiket(): BelongsTo
    {
        return $this->belongsTo(ServiceTicket::class, 'id_tiket_servis');
    }

    /**
     * Relasi ke Produk (Barang).
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_produk');
    }
}
