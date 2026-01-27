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
        Schema::table('peran', function (Blueprint $table) {
            if (!Schema::hasColumn('peran', 'hak_akses')) {
                $table->json('hak_akses')->nullable()->after('nama');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peran', function (Blueprint $table) {
            if (Schema::hasColumn('peran', 'hak_akses')) {
                $table->dropColumn('hak_akses');
            }
        });
    }
};