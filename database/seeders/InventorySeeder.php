<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Admin User if not exists
        $admin = User::firstOrCreate([
            'email' => 'admin@yala.com',
        ], [
            'name' => 'Admin Yala',
            'password' => bcrypt('password'), // Change in production
        ]);

        // 2. Create Categories (High-Tech context)
        $categories = [
            ['name' => 'Laptops', 'slug' => 'laptops', 'description' => 'Notebooks and Ultrabooks'],
            ['name' => 'Processors', 'slug' => 'processors', 'description' => 'Intel and AMD CPUs'],
            ['name' => 'Graphics Cards', 'slug' => 'gpus', 'description' => 'NVIDIA and Radeon Cards'],
            ['name' => 'Peripherals', 'slug' => 'peripherals', 'description' => 'Keyboards, Mice, Headsets'],
            ['name' => 'Storage', 'slug' => 'storage', 'description' => 'SSDs and HDDs'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 3. Create Suppliers
        $suppliers = [
            ['name' => 'PT. Metro Data', 'email' => 'sales@metrodata.co.id'],
            ['name' => 'Astrindo Starvision', 'email' => 'contact@astrindo.co.id'],
            ['name' => 'Synnex Metrodata', 'email' => 'info@synnex.co.id'],
        ];

        foreach ($suppliers as $sup) {
            Supplier::firstOrCreate(['email' => $sup['email']], $sup);
        }

        // 4. Create Products
        $laptopCat = Category::where('slug', 'laptops')->first();
        $gpuCat = Category::where('slug', 'gpus')->first();
        $storageCat = Category::where('slug', 'storage')->first();
        $supplier1 = Supplier::first();

        $products = [
            [
                'category_id' => $laptopCat->id,
                'supplier_id' => $supplier1->id,
                'name' => 'ASUS ROG Strix G16',
                'slug' => 'asus-rog-strix-g16',
                'sku' => 'ROG-G16-001',
                'barcode' => '1234567890123',
                'description' => 'Gaming Laptop with RTX 4060',
                'buy_price' => 25000000,
                'sell_price' => 28500000,
                'stock_quantity' => 10,
                'min_stock_alert' => 5,
            ],
            [
                'category_id' => $gpuCat->id,
                'supplier_id' => $supplier1->id,
                'name' => 'NVIDIA RTX 4090 Founder Edition',
                'slug' => 'rtx-4090-fe',
                'sku' => 'NV-4090-FE',
                'barcode' => '9876543210987',
                'description' => 'Flagship GPU',
                'buy_price' => 30000000,
                'sell_price' => 35000000,
                'stock_quantity' => 2, // Low stock!
                'min_stock_alert' => 3,
            ],
            [
                'category_id' => $storageCat->id,
                'supplier_id' => $supplier1->id,
                'name' => 'Samsung 990 Pro 2TB',
                'slug' => 'samsung-990-pro-2tb',
                'sku' => 'SAM-SSD-990-2TB',
                'barcode' => '4567890123456',
                'description' => 'NVMe M.2 SSD',
                'buy_price' => 2500000,
                'sell_price' => 3200000,
                'stock_quantity' => 50,
                'min_stock_alert' => 10,
            ],
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(['sku' => $prod['sku']], $prod);
        }

        // 5. Create Transactions (History)
        $rog = Product::where('sku', 'ROG-G16-001')->first();
        
        InventoryTransaction::create([
            'product_id' => $rog->id,
            'user_id' => $admin->id,
            'type' => 'in',
            'quantity' => 10,
            'remaining_stock' => 10,
            'notes' => 'Initial Stock',
        ]);
        
        InventoryTransaction::create([
            'product_id' => $rog->id,
            'user_id' => $admin->id,
            'type' => 'out',
            'quantity' => 1,
            'remaining_stock' => 9,
            'notes' => 'Sales Order #1001',
        ]);
    }
}
