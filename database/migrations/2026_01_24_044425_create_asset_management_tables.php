<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('asset_tag')->unique(); // e.g., AST-2026-001
            $table->string('serial_number')->nullable();

            $table->decimal('purchase_price', 15, 2);
            $table->date('purchase_date');
            $table->integer('useful_life_years')->default(4); // Masa manfaat (tahun)

            $table->decimal('current_value', 15, 2); // Nilai buku saat ini
            $table->string('location')->nullable();
            $table->string('condition')->default('good'); // good, damaged, lost, sold

            $table->timestamps();
        });

        Schema::create('asset_depreciations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_asset_id')->constrained()->onDelete('cascade');
            $table->date('depreciation_date');
            $table->decimal('amount', 15, 2); // Nilai penyusutan bulan ini
            $table->decimal('book_value_after', 15, 2); // Nilai buku setelah penyusutan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_depreciations');
        Schema::dropIfExists('company_assets');
    }
};
