<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $store = Warehouse::create(['name' => 'Toko Utama', 'location' => 'Lantai 1']);
        $warehouse = Warehouse::create(['name' => 'Gudang Pusat', 'location' => 'Lantai 2']);

        // Distribusi stok awal (semua masuk Toko Utama dulu)
        $products = Product::all();
        foreach ($products as $product) {
            $store->products()->attach($product->id, ['quantity' => $product->stock_quantity]);
            $warehouse->products()->attach($product->id, ['quantity' => 0]);
        }
    }
}
