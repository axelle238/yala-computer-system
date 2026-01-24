<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rmas', function (Blueprint $table) {
            $table->decimal('refund_amount', 15, 2)->default(0)->after('resolution_type');
            $table->foreignId('supplier_id')->nullable()->after('order_id')->constrained(); // If returning to supplier
            $table->boolean('stock_adjusted')->default(false)->after('status'); // Flag to prevent double stock reduction
        });

        Schema::table('rma_items', function (Blueprint $table) {
            $table->foreignId('replacement_product_id')->nullable()->after('product_id')->constrained('products'); // If swapping with different product
            $table->string('replacement_serial_number')->nullable()->after('serial_number'); // New SN for the customer
        });
    }

    public function down(): void
    {
        Schema::table('rmas', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['refund_amount', 'supplier_id', 'stock_adjusted']);
        });

        Schema::table('rma_items', function (Blueprint $table) {
            $table->dropForeign(['replacement_product_id']);
            $table->dropColumn(['replacement_product_id', 'replacement_serial_number']);
        });
    }
};