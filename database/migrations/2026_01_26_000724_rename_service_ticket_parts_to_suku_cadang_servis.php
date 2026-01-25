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
        if (Schema::hasTable('service_ticket_parts')) {
            Schema::rename('service_ticket_parts', 'suku_cadang_servis');
        }

        if (Schema::hasTable('suku_cadang_servis')) {
            Schema::table('suku_cadang_servis', function (Blueprint $table) {
                if (Schema::hasColumn('suku_cadang_servis', 'service_ticket_id')) {
                    $table->renameColumn('service_ticket_id', 'id_tiket_servis');
                }
                if (Schema::hasColumn('suku_cadang_servis', 'product_id')) {
                    $table->renameColumn('product_id', 'id_produk');
                }
                if (Schema::hasColumn('suku_cadang_servis', 'quantity')) {
                    $table->renameColumn('quantity', 'jumlah');
                }
                if (Schema::hasColumn('suku_cadang_servis', 'price')) {
                    $table->renameColumn('price', 'harga_satuan');
                }
            });
        }
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        if (Schema::hasTable('suku_cadang_servis')) {
            Schema::table('suku_cadang_servis', function (Blueprint $table) {
                if (Schema::hasColumn('suku_cadang_servis', 'id_tiket_servis')) {
                    $table->renameColumn('id_tiket_servis', 'service_ticket_id');
                }
                if (Schema::hasColumn('suku_cadang_servis', 'id_produk')) {
                    $table->renameColumn('id_produk', 'product_id');
                }
                if (Schema::hasColumn('suku_cadang_servis', 'jumlah')) {
                    $table->renameColumn('jumlah', 'quantity');
                }
                if (Schema::hasColumn('suku_cadang_servis', 'harga_satuan')) {
                    $table->renameColumn('harga_satuan', 'price');
                }
            });
            Schema::rename('suku_cadang_servis', 'service_ticket_parts');
        }
    }
};
