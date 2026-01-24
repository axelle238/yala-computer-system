<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ServiceTicket;
use App\Models\ServiceTicketPart;
use App\Models\ServiceTicketProgress;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Technician User
        $tech = User::firstOrCreate(
            ['email' => 'tech@yala.com'],
            [
                'name' => 'Budi Teknisi',
                'password' => bcrypt('password'),
                // 'role' => 'technician', // Assuming no role column or handled elsewhere
            ]
        );

        // 2. Create Categories
        $catPart = Category::firstOrCreate(['slug' => 'spareparts'], ['name' => 'Spareparts']);
        $catService = Category::firstOrCreate(['slug' => 'services'], ['name' => 'Services']);

        // 3. Create Spareparts & Services
        $products = [
            [
                'name' => 'LCD Screen 14" FHD Slim', 
                'sku' => 'PART-LCD-001', 
                'buy_price' => 1000000, 
                'sell_price' => 1200000, 
                'stock_quantity' => 5, 
                'category_id' => $catPart->id,
                'slug' => Str::slug('LCD Screen 14" FHD Slim')
            ],
            [
                'name' => 'SSD NVMe 512GB Gen3', 
                'sku' => 'PART-SSD-512', 
                'buy_price' => 600000, 
                'sell_price' => 750000, 
                'stock_quantity' => 10, 
                'category_id' => $catPart->id,
                'slug' => Str::slug('SSD NVMe 512GB Gen3')
            ],
            [
                'name' => 'RAM 8GB DDR4 SODIMM', 
                'sku' => 'PART-RAM-008', 
                'buy_price' => 350000, 
                'sell_price' => 450000, 
                'stock_quantity' => 20, 
                'category_id' => $catPart->id,
                'slug' => Str::slug('RAM 8GB DDR4 SODIMM')
            ],
            [
                'name' => 'Jasa Install Ulang Windows', 
                'sku' => 'SERV-OS-001', 
                'buy_price' => 0, 
                'sell_price' => 100000, 
                'stock_quantity' => 999, 
                'category_id' => $catService->id,
                'slug' => Str::slug('Jasa Install Ulang Windows')
            ],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['sku' => $p['sku']], $p);
        }

        // 4. Create Service Tickets
        
        // Ticket 1: Diagnosing
        $t1 = ServiceTicket::create([
            'ticket_number' => 'SRV-' . date('ymd') . '001',
            'customer_name' => 'Andi Wijaya',
            'customer_phone' => '081234567890',
            'device_name' => 'Asus ROG Strix G15',
            'serial_number_in' => 'ROG123456789',
            'problem_description' => 'Laptop panas dan sering mati sendiri saat main game.',
            'status' => 'diagnosing',
            'technician_id' => $tech->id,
            'completeness' => ['Charger', 'Unit'],
            'created_at' => Carbon::now()->subDays(2),
        ]);
        
        ServiceTicketProgress::create([
            'service_ticket_id' => $t1->id,
            'status_label' => 'pending',
            'description' => 'Tiket dibuat. Masuk antrian.',
            'technician_id' => $tech->id,
            'created_at' => Carbon::now()->subDays(2),
        ]);
        
        ServiceTicketProgress::create([
            'service_ticket_id' => $t1->id,
            'status_label' => 'diagnosing',
            'description' => 'Mulai pengecekan suhu dan fan.',
            'technician_id' => $tech->id,
            'created_at' => Carbon::now()->subHours(5),
        ]);

        // Ticket 2: Ready
        $t3 = ServiceTicket::create([
            'ticket_number' => 'SRV-' . date('ymd') . '003',
            'customer_name' => 'PT Maju Mundur',
            'customer_phone' => '021-555666',
            'device_name' => 'PC Rakitan Office',
            'serial_number_in' => '-',
            'problem_description' => 'Lambat, minta upgrade SSD & RAM.',
            'status' => 'ready',
            'technician_id' => $tech->id,
            'completeness' => ['CPU Unit'],
            'created_at' => Carbon::now()->subDays(3),
        ]);

        $ssd = Product::where('sku', 'PART-SSD-512')->first();
        
        ServiceTicketPart::create([
            'service_ticket_id' => $t3->id, 
            'product_id' => $ssd->id, 
            'quantity' => 1, 
            'price_per_unit' => $ssd->sell_price, 
            'subtotal' => $ssd->sell_price
        ]);

        ServiceTicketProgress::create([
            'service_ticket_id' => $t3->id, 
            'status_label' => 'ready', 
            'description' => 'Upgrade selesai.', 
            'technician_id' => $tech->id
        ]);
    }
}