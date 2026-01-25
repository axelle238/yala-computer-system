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
        // Cek jika tabel sudah ada untuk menghindari error, jika ada kita modify, jika belum kita create
        if (!Schema::hasTable('company_assets')) {
            Schema::create('company_assets', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('serial_number')->nullable();
                $table->string('location')->nullable();
                $table->string('condition')->default('good'); // good, damaged, lost
                $table->date('purchase_date');
                $table->decimal('purchase_cost', 15, 2)->default(0);
                $table->integer('useful_life_years')->default(5); // Masa manfaat (tahun)
                $table->decimal('current_value', 15, 2)->default(0);
                $table->string('image_path')->nullable();
                $table->timestamps();
            });
        } else {
            // Jika tabel sudah ada tapi kolom kurang (kasus update)
            Schema::table('company_assets', function (Blueprint $table) {
                if (!Schema::hasColumn('company_assets', 'purchase_cost')) {
                    $table->decimal('purchase_cost', 15, 2)->default(0)->after('purchase_date');
                }
                if (!Schema::hasColumn('company_assets', 'useful_life_years')) {
                    $table->integer('useful_life_years')->default(5)->after('purchase_cost');
                }
                if (!Schema::hasColumn('company_assets', 'current_value')) {
                    $table->decimal('current_value', 15, 2)->default(0)->after('useful_life_years');
                }
                if (!Schema::hasColumn('company_assets', 'location')) {
                    $table->string('location')->nullable()->after('serial_number');
                }
                if (!Schema::hasColumn('company_assets', 'image_path')) {
                    $table->string('image_path')->nullable()->after('current_value');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_assets');
    }
};