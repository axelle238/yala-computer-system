<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create Service Histories Table (Timeline)
        Schema::create('service_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(); // Who changed the status
            $table->string('status'); // The new status
            $table->text('notes')->nullable(); // Optional comment
            $table->timestamps();
        });

        // 2. Add columns to Service Items (Inventory & Warranty)
        Schema::table('service_items', function (Blueprint $table) {
            if (! Schema::hasColumn('service_items', 'is_stock_deducted')) {
                $table->boolean('is_stock_deducted')->default(false)->after('note');
            }
            if (! Schema::hasColumn('service_items', 'serial_number')) {
                $table->string('serial_number')->nullable()->after('is_stock_deducted');
            }
            if (! Schema::hasColumn('service_items', 'warranty_duration')) {
                $table->integer('warranty_duration')->nullable()->comment('In Months')->after('serial_number');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_histories');

        Schema::table('service_items', function (Blueprint $table) {
            $table->dropColumn(['is_stock_deducted', 'serial_number', 'warranty_duration']);
        });
    }
};
