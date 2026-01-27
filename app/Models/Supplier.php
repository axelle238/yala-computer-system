<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $guarded = [];

    /**
     * Dapatkan produk yang dipasok oleh pemasok ini.
     */
    public function produk(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
