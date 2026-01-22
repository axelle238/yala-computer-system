<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

/**
 * Class Product
 * 
 * The central entity of the inventory system.
 * Handles stock levels, pricing, and technical specifications.
 * 
 * @package App\Models
 */
class Product extends Model
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
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
        'warranty_duration', // New
        'buy_price',
        'sell_price',
        'stock_quantity',
        'min_stock_alert',
        'image_path',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'specifications' => 'array',
        'buy_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category that owns the product.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the supplier that provides the product.
     *
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the inventory transactions for the product.
     * Used for audit trails and history.
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

    /**
     * Check if the product stock is below the alert threshold.
     *
     * @return bool
     */
    public function hasLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock_alert;
    }
}
