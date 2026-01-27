<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Dapatkan produk-produk dalam kategori ini.
     */
    public function produk(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
