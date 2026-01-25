<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'path_foto_profil')) {
                $table->string('path_foto_profil')->nullable()->after('phone');
            }
            if (! Schema::hasColumn('users', 'nomor_ktp')) {
                $table->string('nomor_ktp', 16)->nullable()->after('path_foto_profil');
            }
            if (! Schema::hasColumn('users', 'alamat_lengkap')) {
                $table->text('alamat_lengkap')->nullable()->after('nomor_ktp');
            }
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['path_foto_profil', 'nomor_ktp', 'alamat_lengkap']);
        });
    }
};
