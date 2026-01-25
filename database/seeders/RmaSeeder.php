<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class RmaSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::where('sku', 'like', 'MOUSE%')->first();
        if (! $product) {
            $product = Product::first();
        }

        $order = Order::create([
            'order_number' => 'ORD-RMA-TEST-'.rand(100, 999),
            'guest_name' => 'Budi Retur',
            'guest_whatsapp' => '08123456789',
            'total_amount' => $product->sell_price,
            'status' => 'completed',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->sell_price,
            // 'subtotal' removed
        ]);
    }
}
