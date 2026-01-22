<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained(); // Nullable for 'Jasa' or non-inventory items
            $table->string('item_name'); // For manual entry or product name snapshot
            $table->integer('quantity')->default(1);
            $table->decimal('cost', 15, 2)->default(0); // Buy price (for profit calc)
            $table->decimal('price', 15, 2)->default(0); // Sell price to customer
            $table->text('note')->nullable();
            $table->timestamps();
        });

        // Add missing column to service_tickets if not exists (defensive)
        if (!Schema::hasColumn('service_tickets', 'estimated_completion')) {
            Schema::table('service_tickets', function (Blueprint $table) {
                $table->dateTime('estimated_completion')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_items');
        
        if (Schema::hasColumn('service_tickets', 'estimated_completion')) {
            Schema::table('service_tickets', function (Blueprint $table) {
                $table->dropColumn('estimated_completion');
            });
        }
    }
};