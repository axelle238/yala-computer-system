<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->decimal('unit_price', 15, 2)->default(0)->after('quantity')->comment('Selling price at moment of transaction');
            $table->decimal('cogs', 15, 2)->default(0)->after('unit_price')->comment('Cost of Goods Sold (Buy Price) at moment of transaction');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropColumn(['unit_price', 'cogs']);
        });
    }
};
