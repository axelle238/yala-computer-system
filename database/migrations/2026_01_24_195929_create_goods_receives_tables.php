<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Header Penerimaan Barang
        Schema::create('goods_receives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->string('gr_number')->unique()->comment('Nomor Bukti Terima Internal');
            $table->string('supplier_delivery_order')->nullable()->comment('Nomor Surat Jalan Supplier');
            $table->date('received_date');
            $table->foreignId('received_by')->constrained('users');
            $table->foreignId('warehouse_id')->default(1)->constrained(); // Default Warehouse ID 1
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Detail Item yang Diterima
        Schema::create('goods_receive_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_receive_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity_ordered')->comment('Snapshot qty order saat terima');
            $table->integer('quantity_received')->comment('Qty fisik yang diterima & bagus');
            $table->integer('quantity_rejected')->default(0)->comment('Qty rusak/ditolak');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_receive_items');
        Schema::dropIfExists('goods_receives');
    }
};