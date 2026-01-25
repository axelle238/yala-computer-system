<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(3, true);
        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'sku' => Str::upper(fake()->bothify('SKU-####')),
            'barcode' => fake()->ean13(),
            'description' => fake()->paragraph(),
            'buy_price' => 50000,
            'sell_price' => 75000,
            'stock_quantity' => 10,
            'min_stock_alert' => 2,
            'is_active' => true,
        ];
    }
}
