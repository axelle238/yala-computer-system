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
        if (Schema::hasTable('service_ticket_progress')) {
            Schema::rename('service_ticket_progress', 'progres_servis');
        }

        if (Schema::hasTable('progres_servis')) {
            Schema::table('progres_servis', function (Blueprint $table) {
                if (Schema::hasColumn('progres_servis', 'service_ticket_id')) {
                    $table->renameColumn('service_ticket_id', 'id_tiket_servis');
                }
                if (Schema::hasColumn('progres_servis', 'status_label')) {
                    $table->renameColumn('status_label', 'status');
                }
                if (Schema::hasColumn('progres_servis', 'description')) {
                    $table->renameColumn('description', 'deskripsi');
                }
                if (Schema::hasColumn('progres_servis', 'technician_id')) {
                    $table->renameColumn('technician_id', 'id_teknisi');
                }
                if (Schema::hasColumn('progres_servis', 'is_public')) {
                    $table->renameColumn('is_public', 'is_publik');
                }
                if (Schema::hasColumn('progres_servis', 'attachment_paths')) {
                    $table->renameColumn('attachment_paths', 'lampiran');
                }
            });
        }
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        if (Schema::hasTable('progres_servis')) {
            Schema::table('progres_servis', function (Blueprint $table) {
                if (Schema::hasColumn('progres_servis', 'id_tiket_servis')) {
                    $table->renameColumn('id_tiket_servis', 'service_ticket_id');
                }
                // ... revert others
            });
            Schema::rename('progres_servis', 'service_ticket_progress');
        }
    }
};
