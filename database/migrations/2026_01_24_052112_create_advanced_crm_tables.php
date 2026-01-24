<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Customer Groups (Tiers)
        Schema::create('customer_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Silver, Gold, Platinum
            $table->string('code')->unique();
            $table->decimal('min_spend', 15, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0); // Diskon otomatis
            $table->string('color')->default('gray'); // UI color
            $table->timestamps();
        });

        // 2. Add columns to Users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('customer_group_id')->nullable()->after('role')->constrained()->onDelete('set null');
            $table->decimal('lifetime_value', 15, 2)->default(0)->after('points'); // Total spend all time
        });

        // 3. Customer Interactions (CRM Log)
        Schema::create('customer_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Customer
            $table->foreignId('staff_id')->constrained('users'); // Staff yang mencatat
            $table->string('type'); // call, meeting, email, note, complaint
            $table->text('content');
            $table->date('interaction_date');
            $table->string('outcome')->nullable(); // solved, follow_up_needed
            $table->timestamps();
        });

        // 4. Loyalty Point Logs (Audit Trail)
        Schema::create('loyalty_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('type'); // earned, redeemed, adjustment
            $table->integer('points'); // + or -
            $table->string('reference_type')->nullable(); // Order, Manual
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_logs');
        Schema::dropIfExists('customer_interactions');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['customer_group_id']);
            $table->dropColumn(['customer_group_id', 'lifetime_value']);
        });
        Schema::dropIfExists('customer_groups');
    }
};