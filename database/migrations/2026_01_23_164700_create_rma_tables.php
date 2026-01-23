<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rmas', function (Blueprint $table) {
            $table->id();
            $table->string('rma_number')->unique(); // RMA-202601-001
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Member who requested
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null'); // Linked to original purchase
            $table->string('guest_name')->nullable(); // For walk-in guest
            $table->string('guest_phone')->nullable();
            
            $table->string('status')->default('pending'); 
            // pending, approved, received_goods, checking, sent_to_distributor, ready_to_pickup, completed, rejected
            
            $table->string('resolution_type')->nullable(); // refund, replacement, repair
            $table->text('reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('rma_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rma_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->string('serial_number')->nullable();
            $table->integer('quantity')->default(1);
            $table->text('condition')->nullable(); // Physical condition check
            $table->text('problem_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rma_items');
        Schema::dropIfExists('rmas');
    }
};