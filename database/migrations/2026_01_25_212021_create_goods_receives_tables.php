<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goods_receives', function (Blueprint $table) {
            $table->id();
            $table->string('grn_number')->unique(); // GRN-YYYYMMDD-XXXX
            $table->foreignId('purchase_order_id')->constrained();
            $table->foreignId('received_by')->constrained('users');
            $table->date('received_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('goods_receive_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_receive_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity_received');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_receive_items');
        Schema::dropIfExists('goods_receives');
    }
};