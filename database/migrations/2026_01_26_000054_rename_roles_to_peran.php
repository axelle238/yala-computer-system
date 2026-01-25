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
        // Ubah nama tabel roles menjadi peran
        if (Schema::hasTable('roles')) {
            Schema::rename('roles', 'peran');
        }

        // Ubah kolom di tabel peran
        if (Schema::hasTable('peran')) {
            Schema::table('peran', function (Blueprint $table) {
                if (Schema::hasColumn('peran', 'name')) {
                    $table->renameColumn('name', 'nama');
                }
                if (Schema::hasColumn('peran', 'permissions')) {
                    $table->renameColumn('permissions', 'hak_akses');
                }
            });
        }

        // Ubah role_id menjadi id_peran di tabel users
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'role_id')) {
                    $table->renameColumn('role_id', 'id_peran');
                }
            });
        }
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'id_peran')) {
                    $table->renameColumn('id_peran', 'role_id');
                }
            });
        }

        if (Schema::hasTable('peran')) {
            Schema::table('peran', function (Blueprint $table) {
                if (Schema::hasColumn('peran', 'nama')) {
                    $table->renameColumn('nama', 'name');
                }
                if (Schema::hasColumn('peran', 'hak_akses')) {
                    $table->renameColumn('hak_akses', 'permissions');
                }
            });
            Schema::rename('peran', 'roles');
        }
    }
};
