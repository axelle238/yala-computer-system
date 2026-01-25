<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. MERDEKA45
            $table->string('name'); // e.g. Promo Kemerdekaan
            $table->string('description')->nullable();

            $table->enum('type', ['fixed', 'percent']);
            $table->decimal('amount', 15, 2); // Nominal diskon atau persentase

            $table->decimal('min_spend', 15, 2)->default(0);
            $table->decimal('max_discount', 15, 2)->nullable(); // Max potongan untuk tipe persen

            $table->integer('usage_limit')->nullable(); // Kuota global (null = unlimited)
            $table->integer('usage_per_user')->default(1); // Max pakai per user

            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('voucher_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->decimal('discount_amount', 15, 2);
            $table->timestamp('used_at');
        });

        // Add voucher column to orders table if not exists
        if (! Schema::hasColumn('orders', 'voucher_code')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('voucher_code')->nullable()->after('discount_amount');
                $table->decimal('voucher_discount', 15, 2)->default(0)->after('voucher_code');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_usages');
        Schema::dropIfExists('vouchers');

        if (Schema::hasColumn('orders', 'voucher_code')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn(['voucher_code', 'voucher_discount']);
            });
        }
    }
};
