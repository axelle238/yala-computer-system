<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creating tables for the Yala Computer Management System.
     */
    public function up(): void
    {
        // 1. Categories: Organizing products (e.g., Laptops, Accessories, Networking)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Suppliers: Vendors who provide the products
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // 3. Products: The core entity.
        // Complex attributes for tech items: Barcodes, SKU, Stock Levels.
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->comment('Stock Keeping Unit - Internal Code');
            $table->string('barcode')->nullable()->unique()->comment('Scannable UPC/EAN code');

            $table->text('description')->nullable();
            $table->json('specifications')->nullable()->comment('JSON for flexible tech specs (CPU, RAM, etc.)');

            $table->decimal('buy_price', 15, 2);
            $table->decimal('sell_price', 15, 2);

            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock_alert')->default(5)->comment('Threshold for low stock warning');

            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Inventory Transactions: The Audit Trail.
        // NEVER just update stock; always record a transaction to know WHY stock changed.
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // Who performed the action

            $table->enum('type', ['in', 'out', 'adjustment', 'return'])->comment('Type of movement');
            $table->integer('quantity')->comment('Positive for IN, Negative for OUT usually, but handled by logic');
            $table->integer('remaining_stock')->comment('Snapshot of stock after this transaction');

            $table->string('reference_number')->nullable()->comment('Invoice # or Order #');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('categories');
    }
};
