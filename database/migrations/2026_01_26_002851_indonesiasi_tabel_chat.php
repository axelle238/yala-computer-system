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
        // Refactor Tabel conversations -> sesi_obrolan
        if (Schema::hasTable('conversations')) {
            Schema::rename('conversations', 'sesi_obrolan');
        }

        if (Schema::hasTable('sesi_obrolan')) {
            Schema::table('sesi_obrolan', function (Blueprint $table) {
                // Rename Columns
                if (Schema::hasColumn('sesi_obrolan', 'customer_id')) $table->renameColumn('customer_id', 'id_pelanggan');
                if (Schema::hasColumn('sesi_obrolan', 'guest_token')) $table->renameColumn('guest_token', 'token_tamu');
                if (Schema::hasColumn('sesi_obrolan', 'subject')) $table->renameColumn('subject', 'topik');
                if (Schema::hasColumn('sesi_obrolan', 'is_closed')) $table->renameColumn('is_closed', 'is_selesai');
            });
        }

        // Refactor Tabel messages -> pesan_obrolan
        if (Schema::hasTable('messages')) {
            Schema::rename('messages', 'pesan_obrolan');
        }

        if (Schema::hasTable('pesan_obrolan')) {
            Schema::table('pesan_obrolan', function (Blueprint $table) {
                // Rename Columns
                if (Schema::hasColumn('pesan_obrolan', 'conversation_id')) $table->renameColumn('conversation_id', 'id_sesi');
                if (Schema::hasColumn('pesan_obrolan', 'user_id')) $table->renameColumn('user_id', 'id_pengguna');
                if (Schema::hasColumn('pesan_obrolan', 'is_admin_reply')) $table->renameColumn('is_admin_reply', 'is_balasan_admin');
                if (Schema::hasColumn('pesan_obrolan', 'body')) $table->renameColumn('body', 'isi');
                if (Schema::hasColumn('pesan_obrolan', 'attachment')) $table->renameColumn('attachment', 'lampiran');
                if (Schema::hasColumn('pesan_obrolan', 'is_read')) $table->renameColumn('is_read', 'is_dibaca');
            });
        }
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        // Revert pesan_obrolan -> messages
        if (Schema::hasTable('pesan_obrolan')) {
            Schema::table('pesan_obrolan', function (Blueprint $table) {
                $table->renameColumn('id_sesi', 'conversation_id');
                $table->renameColumn('id_pengguna', 'user_id');
                $table->renameColumn('is_balasan_admin', 'is_admin_reply');
                $table->renameColumn('isi', 'body');
                $table->renameColumn('lampiran', 'attachment');
                $table->renameColumn('is_dibaca', 'is_read');
            });
            Schema::rename('pesan_obrolan', 'messages');
        }

        // Revert sesi_obrolan -> conversations
        if (Schema::hasTable('sesi_obrolan')) {
            Schema::table('sesi_obrolan', function (Blueprint $table) {
                $table->renameColumn('id_pelanggan', 'customer_id');
                $table->renameColumn('token_tamu', 'guest_token');
                $table->renameColumn('topik', 'subject');
                $table->renameColumn('is_selesai', 'is_closed');
            });
            Schema::rename('sesi_obrolan', 'conversations');
        }
    }
};