<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->string('payable_type'); // Order (AR) or PurchaseOrder (AP)
            $table->unsignedBigInteger('payable_id');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method'); // cash, transfer, qris
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->string('proof_file')->nullable(); // Bukti Transfer
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['payable_type', 'payable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
