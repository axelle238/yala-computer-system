<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Upgrade Orders for Payment Gateway
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('orders', 'payment_url')) {
                $table->string('payment_url')->nullable()->after('snap_token');
            }
            if (!Schema::hasColumn('orders', 'payment_data')) {
                $table->json('payment_data')->nullable()->after('payment_url');
            }
            // Check paid_at before adding
            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_status');
            }
        });

        // 2. Chat System Tables
        if (!Schema::hasTable('conversations')) {
            Schema::create('conversations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('guest_token')->nullable()->index();
                $table->string('subject')->nullable();
                $table->boolean('is_closed')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users');
                $table->boolean('is_admin_reply')->default(false);
                $table->text('body');
                $table->string('attachment')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // ... (keep default)
    }
};
