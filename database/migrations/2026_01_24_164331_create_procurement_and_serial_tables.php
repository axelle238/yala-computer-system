<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Product Serials
        if (!Schema::hasTable('product_serials')) {
            Schema::create('product_serials', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->string('serial_number');
                $table->string('status')->default('available'); // available, reserved, sold, rma, defective
                $table->foreignId('warehouse_id')->constrained();
                
                // Linking to purchase/sales
                $table->foreignId('goods_receive_item_id')->nullable()->constrained('goods_receive_items')->onDelete('set null');
                $table->foreignId('order_item_id')->nullable()->constrained('order_items')->onDelete('set null');
                
                $table->decimal('cost_price', 15, 2)->nullable(); // Specific cost for this unit
                $table->timestamps();

                $table->unique(['product_id', 'serial_number']);
            });
        }

        // 2. Product Bundles
        if (!Schema::hasTable('product_bundles')) {
            Schema::create('product_bundles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->decimal('price', 15, 2);
                $table->string('image_path')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_bundle_items')) {
            Schema::create('product_bundle_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_bundle_id')->constrained('product_bundles')->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->timestamps();
            });
        }

        // 3. Reviews (StoreFront)
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->foreignId('product_id')->constrained();
                $table->foreignId('order_id')->constrained(); // Verified purchase
                $table->integer('rating'); // 1-5
                $table->text('comment')->nullable();
                $table->boolean('is_approved')->default(false); // Moderation
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('product_bundle_items');
        Schema::dropIfExists('product_bundles');
        Schema::dropIfExists('product_serials');
    }
};