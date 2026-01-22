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
        Schema::table('users', function (Blueprint $table) {
            // Role: admin (Full Akses), owner (Lihat Laporan), employee (Terbatas)
            $table->enum('role', ['admin', 'owner', 'employee'])->default('employee')->after('email');
            
            // Hak akses granular untuk pegawai (disimpan sebagai JSON array)
            // Contoh: ["create_transaction", "view_products"]
            $table->json('access_rights')->nullable()->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'access_rights']);
        });
    }
};