<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('warranty_duration')->default(12)->after('specifications')->comment('Durasi garansi dalam bulan');
        });

        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->text('serial_numbers')->nullable()->after('quantity')->comment('SN barang yang dijual, dipisah koma');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['warranty_duration']);
        });
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropColumn(['serial_numbers']);
        });
    }
};