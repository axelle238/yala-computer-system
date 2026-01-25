<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProcurementSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Supplier
        $supplier = Supplier::firstOrCreate(
            ['name' => 'PT Distributor Komputer Pusat'],
            [
                'email' => 'sales@distributor.com',
                'phone' => '021-88997766',
                'address' => 'Mangga Dua Mall Lt. 4',
                'contact_person' => 'Bpk. Hartono',
            ]
        );

        $ssd = Product::where('sku', 'PART-SSD-512')->first();
        if ($ssd) {
            $ssd->update(['stock_quantity' => 2]);
        }

        // 3. Create Purchase Order (Status: Ordered)
        // Use random suffix to avoid collision during re-seeding
        $poNumber1 = 'PO-'.date('Ymd').'-'.rand(1000, 9999);

        $po = PurchaseOrder::create([
            'po_number' => $poNumber1,
            'supplier_id' => $supplier->id,
            'status' => 'ordered',
            'delivery_status' => 'pending',
            'order_date' => Carbon::now()->subDays(3),
            'total_amount' => 750000 * 20,
            'created_by' => User::first()->id ?? 1,
        ]);

        if ($ssd) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'product_id' => $ssd->id,
                'quantity_ordered' => 20,
                'quantity_received' => 0,
                'buy_price' => 600000,
                'subtotal' => 12000000,
            ]);
        }

        // 5. Create Another PO (Partial)
        $poNumber2 = 'PO-'.date('Ymd').'-'.rand(1000, 9999);

        $poPartial = PurchaseOrder::create([
            'po_number' => $poNumber2,
            'supplier_id' => $supplier->id,
            'status' => 'ordered',
            'delivery_status' => 'partial',
            'order_date' => Carbon::now()->subDays(5),
            'total_amount' => 5000000,
            'created_by' => User::first()->id ?? 1,
        ]);

        if ($ssd) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $poPartial->id,
                'product_id' => $ssd->id,
                'quantity_ordered' => 10,
                'quantity_received' => 5,
                'buy_price' => 600000,
                'subtotal' => 6000000,
            ]);
        }
    }
}
