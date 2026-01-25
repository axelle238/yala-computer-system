<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Expenses
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable();
                $table->string('description');
                $table->decimal('amount', 15, 2);
                $table->string('category')->default('operational');
                $table->date('date');
                $table->string('payment_method')->default('cash');
                $table->string('receipt_path')->nullable();
                $table->timestamps();
            });
        }

        // 2. Wishlists
        if (!Schema::hasTable('wishlists')) {
            Schema::create('wishlists', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }

        // 3. Vouchers
        if (!Schema::hasTable('vouchers')) {
            Schema::create('vouchers', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('type')->default('fixed'); // fixed, percentage
                $table->decimal('amount', 15, 2);
                $table->decimal('min_spend', 15, 2)->default(0);
                $table->integer('quota')->default(0);
                $table->boolean('is_active')->default(true);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->timestamps();
            });
        }

        // 4. Voucher Usages
        if (!Schema::hasTable('voucher_usages')) {
            Schema::create('voucher_usages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('voucher_id')->constrained();
                $table->foreignId('user_id')->constrained();
                $table->foreignId('order_id')->nullable();
                $table->decimal('discount_amount', 15, 2);
                $table->timestamp('used_at');
                $table->timestamps();
            });
        }

        // 5. Flash Sales
        if (!Schema::hasTable('flash_sales')) {
            Schema::create('flash_sales', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->decimal('discount_price', 15, 2);
                $table->integer('quota')->default(10);
                $table->integer('sold_quantity')->default(0);
                $table->dateTime('start_time');
                $table->dateTime('end_time');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 6. Articles (News/Blog)
        if (!Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('content');
                $table->string('category')->default('news');
                $table->string('image_path')->nullable();
                $table->boolean('is_published')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->foreignId('user_id')->nullable();
                $table->timestamps();
            });
        }

        // 7. Contact Messages
        if (!Schema::hasTable('contact_messages')) {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('subject')->nullable();
                $table->text('message');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }

        // 8. Inventory Transfers
        if (!Schema::hasTable('inventory_transfers')) {
            Schema::create('inventory_transfers', function (Blueprint $table) {
                $table->id();
                $table->string('transfer_number')->unique();
                $table->foreignId('source_warehouse_id')->nullable(); // Simple int if warehouse table not exists yet
                $table->foreignId('destination_warehouse_id')->nullable();
                $table->string('status')->default('pending'); // pending, completed
                $table->text('notes')->nullable();
                $table->foreignId('user_id')->nullable();
                $table->date('transfer_date');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('inventory_transfer_items')) {
            Schema::create('inventory_transfer_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inventory_transfer_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained();
                $table->integer('quantity');
                $table->timestamps();
            });
        }
        
        // 9. Saved Builds (PC Builder)
        if (!Schema::hasTable('saved_builds')) {
            Schema::create('saved_builds', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('total_price_estimated', 15, 2)->default(0);
                $table->json('components')->nullable(); // Store parts IDs
                $table->string('share_token')->nullable();
                $table->timestamps();
            });
        }
        
        // 10. Reviews & Discussions
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->foreignId('product_id')->constrained();
                $table->integer('rating');
                $table->text('comment')->nullable();
                $table->boolean('is_approved')->default(true);
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('product_discussions')) {
            Schema::create('product_discussions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->foreignId('product_id')->constrained();
                $table->text('message');
                $table->foreignId('parent_id')->nullable()->constrained('product_discussions')->cascadeOnDelete();
                $table->timestamps();
            });
        }
        
        // 11. Activity Logs (Simple version if spatie not used)
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable();
                $table->string('action'); // create, update, delete, login
                $table->string('model_type')->nullable();
                $table->unsignedBigInteger('model_id')->nullable();
                $table->text('description')->nullable();
                $table->json('properties')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Drop tables in reverse order of dependency
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('product_discussions');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('saved_builds');
        Schema::dropIfExists('inventory_transfer_items');
        Schema::dropIfExists('inventory_transfers');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('flash_sales');
        Schema::dropIfExists('voucher_usages');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('expenses');
    }
};