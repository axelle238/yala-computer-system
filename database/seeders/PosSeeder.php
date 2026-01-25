<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PosSeeder extends Seeder
{
    public function run(): void
    {
        $cats = ['Mouse', 'Keyboard', 'Headset', 'Monitor', 'Accessories'];

        foreach ($cats as $c) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($c)],
                ['name' => $c]
            );

            // Create 3 products per category
            for ($i = 1; $i <= 3; $i++) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => "$c Gaming Pro X$i",
                    'slug' => Str::slug("$c Gaming Pro X$i"),
                    'sku' => strtoupper(substr($c, 0, 3)).rand(1000, 9999),
                    'description' => 'High quality gaming gear',
                    'buy_price' => rand(100, 500) * 1000,
                    'sell_price' => rand(600, 900) * 1000,
                    'stock_quantity' => rand(10, 50),
                    'is_active' => true,
                ]);
            }
        }
    }
}
