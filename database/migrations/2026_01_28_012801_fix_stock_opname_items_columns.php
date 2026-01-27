<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->renameColumn('difference', 'variance');
        });

        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->integer('variance')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->integer('variance')->nullable(false)->change();
        });

        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->renameColumn('variance', 'difference');
        });
    }
};
