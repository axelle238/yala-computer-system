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
        Schema::rename('contact_messages', 'pesan_pelanggan');

        Schema::table('pesan_pelanggan', function (Blueprint $table) {
            $table->renameColumn('name', 'nama');
            $table->renameColumn('email', 'surel');
            $table->renameColumn('subject', 'subjek');
            $table->renameColumn('message', 'isi_pesan');
            // Status tetap status (Bahasa Indonesia & Inggris sama)
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('pesan_pelanggan', function (Blueprint $table) {
            $table->renameColumn('nama', 'name');
            $table->renameColumn('surel', 'email');
            $table->renameColumn('subjek', 'subject');
            $table->renameColumn('isi_pesan', 'message');
        });

        Schema::rename('pesan_pelanggan', 'contact_messages');
    }
};
