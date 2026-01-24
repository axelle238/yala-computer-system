<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'has_serial_number')) {
                $table->boolean('has_serial_number')->default(false)->after('sku')->comment('Wajib input SN saat Goods Receive');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'has_serial_number')) {
                $table->dropColumn('has_serial_number');
            }
        });
    }
};