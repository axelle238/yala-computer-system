<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add Tracking Number to Orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_tracking_number')->nullable()->after('shipping_cost');
            $table->timestamp('shipped_at')->nullable()->after('shipping_tracking_number');
        });

        // Stock Opname (Audit Stok)
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->string('opname_number')->unique();
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete(); // Jika multi-gudang
            $table->foreignId('creator_id')->constrained('users'); // Staff gudang
            $table->foreignId('approver_id')->nullable()->constrained('users'); // Manager
            $table->string('status')->default('draft'); // draft, submitted, approved, rejected
            $table->date('opname_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_opname_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('system_stock'); // Stok di sistem saat opname dibuat
            $table->integer('physical_stock'); // Stok fisik yang dihitung
            $table->integer('difference'); // Selisih (Physical - System)
            $table->text('notes')->nullable(); // Alasan selisih
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opname_items');
        Schema::dropIfExists('stock_opnames');
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_tracking_number', 'shipped_at']);
        });
    }
};