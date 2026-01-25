<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Manifest Pengiriman (Kumpulan paket untuk di-pickup kurir)
        Schema::create('shipping_manifests', function (Blueprint $table) {
            $table->id();
            $table->string('manifest_number')->unique(); // MAN-20260124-001
            $table->string('courier_name'); // JNE, JNT, SiCepat
            $table->string('courier_service')->nullable(); // REG, YES, HALU
            $table->foreignId('created_by')->constrained('users');
            $table->enum('status', ['draft', 'ready_for_pickup', 'picked_up'])->default('draft');
            $table->timestamp('pickup_time')->nullable();
            $table->timestamps();
        });

        // 2. Detail Pengiriman per Order
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipping_manifest_id')->nullable()->constrained()->onDelete('set null');

            $table->string('tracking_number')->nullable(); // Resi
            $table->string('courier_name');
            $table->decimal('weight_kg', 8, 2)->default(1);
            $table->decimal('shipping_cost', 15, 2)->default(0);

            $table->string('status')->default('pending'); // pending, shipped, delivered, returned
            $table->text('tracking_history')->nullable(); // JSON log of status updates

            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('shipping_manifests');
    }
};
