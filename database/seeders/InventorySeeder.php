<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
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
            'role' => 'admin',
        ]);

        // 2. Create Categories (Complete for PcBuilder)
        $categories = [
            ['name' => 'Laptops', 'slug' => 'laptops', 'description' => 'Notebooks and Ultrabooks'],
            ['name' => 'Processors (CPU)', 'slug' => 'processors', 'description' => 'Intel and AMD CPUs'],
            ['name' => 'Motherboards', 'slug' => 'motherboards', 'description' => 'ATX, mATX, ITX Mobos'],
            ['name' => 'Memory (RAM)', 'slug' => 'rams', 'description' => 'DDR4, DDR5 Modules'],
            ['name' => 'Graphics Cards (VGA)', 'slug' => 'gpus', 'description' => 'NVIDIA and Radeon Cards'],
            ['name' => 'Storage (SSD/HDD)', 'slug' => 'storage', 'description' => 'NVMe, SATA SSDs, HDDs'],
            ['name' => 'Power Supply (PSU)', 'slug' => 'psus', 'description' => 'Modular, Non-Modular PSUs'],
            ['name' => 'Casing PC', 'slug' => 'cases', 'description' => 'Mid Tower, Full Tower Cases'],
            ['name' => 'Peripherals', 'slug' => 'peripherals', 'description' => 'Keyboards, Mice, Headsets'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 3. Create Suppliers
        $suppliers = [
            ['name' => 'PT. Metro Data', 'email' => 'sales@metrodata.co.id', 'phone' => '021-1234567'],
            ['name' => 'Astrindo Starvision', 'email' => 'contact@astrindo.co.id', 'phone' => '021-7654321'],
            ['name' => 'Synnex Metrodata', 'email' => 'info@synnex.co.id', 'phone' => '021-9876543'],
        ];

        foreach ($suppliers as $sup) {
            Supplier::updateOrCreate(['email' => $sup['email']], $sup);
        }

        $supplier1 = Supplier::first();

        // 4. Create Products (Comprehensive List)
        $productsData = [
            // Laptops
            [
                'category_slug' => 'laptops',
                'name' => 'ASUS ROG Strix G16 (2024)',
                'sku' => 'ROG-G16-2024',
                'description' => 'Intel Core i9-13980HX, RTX 4060 8GB, 16GB DDR5, 1TB SSD, 16" QHD+ 240Hz',
                'price' => 28500000,
                'warranty' => 24,
                'stock' => 5,
                'image' => null, // Placeholder handled in view
            ],
            // Processors
            [
                'category_slug' => 'processors',
                'name' => 'Intel Core i5-13600K',
                'sku' => 'INTEL-13600K',
                'description' => '14 Cores (6P + 8E), up to 5.1 GHz, LGA1700',
                'price' => 5200000,
                'warranty' => 36,
                'stock' => 20,
            ],
            [
                'category_slug' => 'processors',
                'name' => 'AMD Ryzen 7 7800X3D',
                'sku' => 'AMD-7800X3D',
                'description' => '8 Cores, 16 Threads, 3D V-Cache, AM5 Socket',
                'price' => 6800000,
                'warranty' => 36,
                'stock' => 15,
            ],
            // Motherboards
            [
                'category_slug' => 'motherboards',
                'name' => 'ASUS TUF Gaming Z790-PLUS WiFi',
                'sku' => 'ASUS-Z790-TUF',
                'description' => 'LGA1700, DDR5, PCIe 5.0, WiFi 6E',
                'price' => 4500000,
                'warranty' => 36,
                'stock' => 10,
            ],
            [
                'category_slug' => 'motherboards',
                'name' => 'MSI MAG B650 Tomahawk WiFi',
                'sku' => 'MSI-B650-TOM',
                'description' => 'AM5, DDR5, PCIe 4.0, WiFi 6E',
                'price' => 3800000,
                'warranty' => 36,
                'stock' => 10,
            ],
            // RAM
            [
                'category_slug' => 'rams',
                'name' => 'Corsair Vengeance RGB 32GB (2x16GB) DDR5 6000MHz',
                'sku' => 'COR-DDR5-32GB',
                'description' => 'DDR5, 6000MHz, CL30, RGB Lighting',
                'price' => 2400000,
                'warranty' => 120, // Limited Lifetime
                'stock' => 25,
            ],
            // GPUs
            [
                'category_slug' => 'gpus',
                'name' => 'NVIDIA GeForce RTX 4070 Ti Super',
                'sku' => 'NV-4070TIS',
                'description' => '16GB GDDR6X, DLSS 3.5, Ray Tracing',
                'price' => 14500000,
                'warranty' => 36,
                'stock' => 8,
            ],
            [
                'category_slug' => 'gpus',
                'name' => 'ASUS ROG Strix RTX 4090 OC',
                'sku' => 'ASUS-4090-ROG',
                'description' => '24GB GDDR6X, The Ultimate GPU',
                'price' => 32000000,
                'warranty' => 36,
                'stock' => 2,
            ],
            // Storage
            [
                'category_slug' => 'storage',
                'name' => 'Samsung 990 Pro 1TB NVMe',
                'sku' => 'SAM-990-1TB',
                'description' => 'PCIe 4.0 NVMe M.2 SSD, Up to 7450 MB/s',
                'price' => 1800000,
                'warranty' => 60,
                'stock' => 50,
            ],
            // PSU
            [
                'category_slug' => 'psus',
                'name' => 'Corsair RM850e 850W 80+ Gold',
                'sku' => 'COR-RM850E',
                'description' => 'Fully Modular, ATX 3.0 Compliant',
                'price' => 2100000,
                'warranty' => 84,
                'stock' => 12,
            ],
            // Cases
            [
                'category_slug' => 'cases',
                'name' => 'Lian Li O11 Dynamic Evo',
                'sku' => 'LIANLI-O11D',
                'description' => 'Mid Tower, Tempered Glass, Dual Chamber',
                'price' => 2600000,
                'warranty' => 12,
                'stock' => 5,
            ],
        ];

        foreach ($productsData as $data) {
            $cat = Category::where('slug', $data['category_slug'])->first();
            if (! $cat) {
                continue;
            }

            $product = Product::updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'category_id' => $cat->id,
                    'supplier_id' => $supplier1->id,
                    'name' => $data['name'],
                    'slug' => Str::slug($data['name']),
                    'barcode' => Str::random(13),
                    'description' => $data['description'],
                    'buy_price' => $data['price'] * 0.85,
                    'sell_price' => $data['price'],
                    'stock_quantity' => $data['stock'],
                    'min_stock_alert' => 2,
                    'warranty_duration' => $data['warranty'],
                    'is_active' => true,
                ]
            );

            // Initial Stock Transaction
            if ($product->wasRecentlyCreated) {
                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'user_id' => $admin->id,
                    'type' => 'in',
                    'quantity' => $data['stock'],
                    'remaining_stock' => $data['stock'],
                    'notes' => 'Initial Seed Stock',
                    'created_at' => now()->subMonth(), // Make it look like it was added last month
                ]);
            }
        }

        // 5. Create specific transactions for Warranty Check
        // Create a transaction that happened 6 months ago for a GPU
        $gpu = Product::where('sku', 'NV-4070TIS')->first();
        if ($gpu) {
            InventoryTransaction::create([
                'product_id' => $gpu->id,
                'user_id' => $admin->id,
                'type' => 'out',
                'quantity' => 1,
                'remaining_stock' => $gpu->stock_quantity - 1,
                'serial_numbers' => 'SN-VALID-12345',
                'notes' => 'Customer Sale #INV-001',
                'created_at' => now()->subMonths(6),
            ]);
        }

        // Create an expired transaction (purchased 4 years ago for a 3 year warranty item)
        $cpu = Product::where('sku', 'INTEL-13600K')->first();
        if ($cpu) {
            InventoryTransaction::create([
                'product_id' => $cpu->id,
                'user_id' => $admin->id,
                'type' => 'out',
                'quantity' => 1,
                'remaining_stock' => $cpu->stock_quantity - 1,
                'serial_numbers' => 'SN-EXPIRED-67890',
                'notes' => 'Old Customer Sale',
                'created_at' => now()->subYears(4),
            ]);
        }
    }
}
