<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Warehouse (Gudang)
 * 
 * Model untuk mengelola lokasi penyimpanan fisik stok.
 * 
 * @package App\Models
 */
class Warehouse extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'location', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Produk.
     * Gudang memiliki banyak produk dengan kuantitas tertentu.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}