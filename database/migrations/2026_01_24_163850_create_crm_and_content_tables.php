<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Loyalty Logs (History Point)
        if (!Schema::hasTable('loyalty_logs')) {
            Schema::create('loyalty_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->integer('points'); // Bisa positif (earn) atau negatif (redeem)
                $table->string('type'); // purchase, referral, adjustment, redemption
                $table->string('description')->nullable();
                $table->string('reference_id')->nullable(); // Order ID or other ref
                $table->timestamps();
            });
        }

        // 2. Add Referral Code to Users (if not exists)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code')->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'referred_by')) {
                $table->foreignId('referred_by')->nullable()->after('referral_code')->constrained('users')->onDelete('set null');
            }
        });

        // 3. FAQ Categories
        if (!Schema::hasTable('faq_categories')) {
            Schema::create('faq_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->integer('order_index')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 4. FAQs
        if (!Schema::hasTable('faqs')) {
            Schema::create('faqs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('faq_category_id')->constrained()->onDelete('cascade');
                $table->string('question');
                $table->text('answer');
                $table->boolean('is_published')->default(true);
                $table->integer('order_index')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('faq_categories');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['referral_code', 'referred_by']);
        });
        Schema::dropIfExists('loyalty_logs');
    }
};