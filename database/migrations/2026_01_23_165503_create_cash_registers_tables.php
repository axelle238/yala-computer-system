<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Shift / Register
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Kasir yg buka
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();

            $table->decimal('opening_cash', 15, 2)->default(0); // Modal Awal
            $table->decimal('closing_cash', 15, 2)->default(0); // Uang fisik saat tutup (input user)
            $table->decimal('expected_cash', 15, 2)->default(0); // Hitungan sistem
            $table->decimal('variance', 15, 2)->default(0); // Selisih

            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        // 2. Modifikasi Tabel Orders untuk link ke Register
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('cash_register_id')->nullable()->after('user_id')->constrained();
            $table->string('payment_method')->default('cash')->after('payment_status'); // cash, transfer, qris
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['cash_register_id']);
            $table->dropColumn(['cash_register_id', 'payment_method']);
        });
        Schema::dropIfExists('cash_registers');
    }
};
