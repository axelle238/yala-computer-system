<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Quotations (Penawaran)
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique(); // QT-2024...
            $table->foreignId('customer_id')->constrained(); // Wajib ada customer
            $table->foreignId('user_id')->constrained(); // Sales
            $table->date('valid_until');
            $table->decimal('total_amount', 15, 2);
            $table->string('status')->default('draft'); // draft, sent, approved, rejected, converted
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });

        // 2. Update Customers (Credit Limit)
        Schema::table('customers', function (Blueprint $table) {
            $table->decimal('credit_limit', 15, 2)->default(0)->after('points');
            $table->decimal('current_debt', 15, 2)->default(0)->after('credit_limit');
            $table->integer('top_days')->default(0)->after('current_debt'); // Term of Payment (days)
        });

        // 3. Update Orders (Due Date for Credit)
        Schema::table('orders', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('due_date');
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['credit_limit', 'current_debt', 'top_days']);
        });
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotations');
    }
};
