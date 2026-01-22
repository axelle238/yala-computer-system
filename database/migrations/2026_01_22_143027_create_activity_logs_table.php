<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Siapa yang melakukan
            $table->string('action'); // created, updated, deleted, login, logout
            $table->string('model_type'); // App\Models\Product
            $table->unsignedBigInteger('model_id'); // ID produk/transaksi
            $table->text('description')->nullable(); // "Mengubah harga produk X"
            $table->json('properties')->nullable(); // Data sebelum dan sesudah {old: ..., new: ...}
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};