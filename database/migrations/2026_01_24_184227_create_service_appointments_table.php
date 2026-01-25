<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('service_appointments')) {
            Schema::create('service_appointments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Registered user
                $table->string('guest_name')->nullable();
                $table->string('guest_phone')->nullable();
                $table->string('device_type'); // PC, Laptop, Printer
                $table->text('problem_description');
                $table->dateTime('appointment_date');
                $table->string('status')->default('scheduled'); // scheduled, completed, cancelled, missed
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_appointments');
    }
};
