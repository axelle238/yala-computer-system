<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_serials')) {
            Schema::create('product_serials', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->foreignId('warehouse_id')->default(1); // Lokasi saat ini
                $table->string('serial_number')->unique(); // SN unik
                $table->string('status')->default('available'); // available, sold, rma, returned_to_supplier, broken
                
                // Riwayat
                $table->foreignId('purchase_order_id')->nullable(); // Asal barang
                $table->foreignId('order_id')->nullable(); // Terjual di order mana
                $table->decimal('buy_price', 15, 2)->default(0); // HPP spesifik batch ini
                
                $table->timestamps();
            });
        }
        
        // Tambahkan flag 'has_serial_number' ke tabel products jika belum ada
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'has_serial_number')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('has_serial_number')->default(false)->after('sku');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_serials');
        if (Schema::hasColumn('products', 'has_serial_number')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('has_serial_number');
            });
        }
    }
};