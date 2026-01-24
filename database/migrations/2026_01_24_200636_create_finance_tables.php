<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Cash Transaction belum ada, jadi kita buat
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_register_id')->constrained()->onDelete('cascade');
            $table->string('transaction_number')->unique();
            
            // Tipe Transaksi
            $table->enum('type', ['in', 'out'])->comment('Masuk / Keluar');
            $table->string('category')->comment('Servis, Penjualan, Operasional, Prive, dll');
            
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            
            // Referensi ke dokumen lain (Polymorphic)
            $table->nullableMorphs('reference'); 
            
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};
