<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Upgrade Products for PC Builder Compatibility
        Schema::table('products', function (Blueprint $table) {
            $table->string('socket_type')->nullable()->index(); // LGA1700, AM5, etc.
            $table->string('memory_type')->nullable()->index(); // DDR4, DDR5
            $table->string('form_factor')->nullable(); // ATX, mATX, ITX
            $table->integer('tdp_watts')->nullable(); // For PSU calculation
            $table->integer('wattage')->nullable(); // For PSU products
        });

        // 2. Customer Orders (Sales)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Nullable for guest checkout if allowed later
            $table->string('guest_name')->nullable();
            $table->string('guest_whatsapp')->nullable();
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 15, 2);
            $table->string('status')->default('pending'); // pending, processing, completed, cancelled
            $table->string('payment_status')->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->decimal('price', 15, 2); // Snapshot of price at time of purchase
            $table->timestamps();
        });

        // 3. Saved PC Builds
        Schema::create('saved_builds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('total_price_estimated', 15, 2);
            $table->json('components'); // Store IDs: { "cpu": 1, "gpu": 5, ... }
            $table->string('share_token')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_builds');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['socket_type', 'memory_type', 'form_factor', 'tdp_watts', 'wattage']);
        });
    }
};