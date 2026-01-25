<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // Contoh: SRV-20240101-001
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('device_name'); // Asus ROG, PC Rakitan, dll
            $table->text('problem_description'); // Keluhan: Mati total, lemot, dll
            $table->enum('status', ['pending', 'diagnosing', 'waiting_part', 'repairing', 'ready', 'picked_up', 'cancelled'])->default('pending');
            $table->decimal('estimated_cost', 15, 2)->default(0);
            $table->decimal('final_cost', 15, 2)->default(0);
            $table->text('technician_notes')->nullable(); // Catatan teknisi internal
            $table->foreignId('technician_id')->nullable()->constrained('users'); // Siapa yang mengerjakan (User ID)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_tickets');
    }
};
