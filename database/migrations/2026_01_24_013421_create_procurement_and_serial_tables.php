<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Header Penerimaan Barang
        Schema::create('goods_receives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('received_by')->constrained('users'); // Staff gudang

            $table->string('grn_number')->unique(); // GRN-20260124-001
            $table->string('supplier_do_number')->nullable(); // No Surat Jalan Supplier
            $table->date('received_date');

            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'finalized'])->default('draft');
            $table->timestamps();
        });

        // 2. Detail Item Penerimaan
        Schema::create('goods_receive_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_receive_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();

            $table->integer('qty_ordered_snapshot'); // Qty total di PO saat itu
            $table->integer('qty_received'); // Qty yang diterima di pengiriman ini

            $table->timestamps();
        });

        // 3. Master Data Serial Number (Jantung Inventory Toko Komputer)
        Schema::create('product_serials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->string('serial_number')->index();

            // Lifecycle Tracking
            $table->foreignId('goods_receive_id')->nullable()->constrained(); // Masuk dari GRN mana
            $table->foreignId('order_id')->nullable()->constrained(); // Terjual di Order mana

            $table->enum('status', ['available', 'reserved', 'sold', 'rma_vendor', 'rma_customer', 'lost'])->default('available');

            $table->timestamps();

            // Unique per product (or global unique, depending on policy. Usually per product)
            $table->unique(['product_id', 'serial_number']);
        });

        // 4. Update PO Table to track status better
        if (! Schema::hasColumn('purchase_orders', 'delivery_status')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->enum('delivery_status', ['pending', 'partial', 'received'])->default('pending')->after('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_serials');
        Schema::dropIfExists('goods_receive_items');
        Schema::dropIfExists('goods_receives');

        if (Schema::hasColumn('purchase_orders', 'delivery_status')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->dropColumn('delivery_status');
            });
        }
    }
};
