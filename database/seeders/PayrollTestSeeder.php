<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ServiceTicket;
use App\Models\ServiceTicketPart;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PayrollTestSeeder extends Seeder
{
    public function run(): void
    {
        $tech = User::where('email', 'tech@yala.com')->first();
        if (! $tech) {
            return;
        }

        // Create Completed Ticket for Commission Calculation
        $ticket = ServiceTicket::create([
            'ticket_number' => 'SRV-COM-'.rand(100, 999),
            'customer_name' => 'Client Komisi Test',
            'customer_phone' => '0811223344',
            'device_name' => 'PC Gaming High End',
            'problem_description' => 'Upgrade Full Set', // Mandatory Field
            'status' => 'picked_up', // Status Wajib untuk Komisi
            'technician_id' => $tech->id,
            'final_cost' => 2500000, // Revenue
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now()->subDays(1), // Completed recently
        ]);

        // Add dummy parts to justify cost
        $ssd = Product::where('sku', 'PART-SSD-512')->first();
        if ($ssd) {
            ServiceTicketPart::create([
                'service_ticket_id' => $ticket->id,
                'product_id' => $ssd->id,
                'quantity' => 1,
                'price_per_unit' => $ssd->sell_price,
                'subtotal' => $ssd->sell_price,
            ]);
        }
    }
}
