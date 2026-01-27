<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Product (Produk)
 *
 * Entitas pusat dalam sistem inventaris.
 * Menangani tingkat stok, harga, dan spesifikasi teknis.
 */
class Product extends Model
{
    use HasFactory, LogsActivity;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'slug',
        'sku',
        'barcode',
        'description',
        'specifications',
        'weight',
        'warranty_duration',
        'buy_price',
        'sell_price',
        'stock_quantity',
        'min_stock_alert',
        'image_path',
        'is_active',
        'has_serial_number',
    ];

    /**
     * Atribut yang harus dikonversi tipe datanya.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'specifications' => 'array',
        'buy_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'is_active' => 'boolean',
        'has_serial_number' => 'boolean',
    ];

    public function serial(): HasMany
    {
        return $this->hasMany(ProductSerial::class);
    }

    /**
     * Dapatkan kategori pemilik produk.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Dapatkan pemasok yang menyediakan produk.
     */
    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Dapatkan transaksi inventaris untuk produk.
     * Digunakan untuk jejak audit dan riwayat.
     */
    public function transaksi(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function obralKilat(): HasMany
    {
        return $this->hasMany(FlashSale::class);
    }

    public function ulasan(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Periksa apakah stok produk di bawah ambang batas peringatan.
     */
    public function stokMenipis(): bool
    {
        return $this->stock_quantity <= $this->min_stock_alert;
    }

    // --- Aksesor Rakit PC ---

    public function getSocketTypeAttribute()
    {
        return $this->specifications['socket'] ?? null;
    }

    public function getMemoryTypeAttribute()
    {
        return $this->specifications['memory_type'] ?? null;
    }

    public function getFormFactorAttribute()
    {
        return $this->specifications['form_factor'] ?? null;
    }

    public function getTdpWattsAttribute()
    {
        return $this->specifications['tdp'] ?? 0;
    }

    public function getPsuWattageAttribute()
    {
        return $this->specifications['wattage'] ?? 0;
    }
}
