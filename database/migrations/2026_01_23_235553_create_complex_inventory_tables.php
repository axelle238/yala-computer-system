<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Product Bundles (One-to-Many products)
        Schema::create('product_bundles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_product_id')->constrained('products')->cascadeOnDelete(); // The Package SKU
            $table->foreignId('child_product_id')->constrained('products'); // The Component SKU
            $table->integer('quantity')->default(1); // Qty needed per bundle
            $table->timestamps();
        });

        // Add flag to products table to identify bundles
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_bundle')->default(false)->after('is_active');
        });

        // 2. Purchase Requisitions (PR) -> Pre-PO
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number')->unique();
            $table->foreignId('requested_by')->constrained('users');
            $table->date('required_date');
            $table->string('status')->default('pending'); // pending, approved, rejected, converted_to_po
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('purchase_requisition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_requisition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity_requested');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_requisition_items');
        Schema::dropIfExists('purchase_requisitions');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_bundle');
        });

        Schema::dropIfExists('product_bundles');
    }
};
