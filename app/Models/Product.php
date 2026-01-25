<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

/**
 * Class Product (Produk)
 * 
 * Entitas pusat dalam sistem inventaris.
 * Menangani tingkat stok, harga, dan spesifikasi teknis.
 * 
 * @package App\Models
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

    public function serials()
    {
        return $this->hasMany(ProductSerial::class);
    }

    /**
     * Dapatkan kategori pemilik produk.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Dapatkan pemasok yang menyediakan produk.
     *
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Dapatkan transaksi inventaris untuk produk.
     * Digunakan untuk jejak audit dan riwayat.
     *
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function flashSales()
    {
        return $this->hasMany(FlashSale::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Periksa apakah stok produk di bawah ambang batas peringatan.
     *
     * @return bool
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