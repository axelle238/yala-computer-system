<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Address Book
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label')->default('Rumah'); // Rumah, Kantor, dll
            $table->string('recipient_name');
            $table->string('phone_number');
            $table->text('address_line');
            $table->string('city');
            $table->string('postal_code')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // 2. Product Discussions (Q&A)
        Schema::create('product_discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->foreignId('parent_id')->nullable()->constrained('product_discussions')->cascadeOnDelete(); // For replies
            $table->boolean('is_admin_reply')->default(false);
            $table->timestamps();
        });

        // 3. Service Appointments
        Schema::create('service_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('device_type'); // Laptop, PC, Printer
            $table->text('problem_description');
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_appointments');
        Schema::dropIfExists('product_discussions');
        Schema::dropIfExists('user_addresses');
    }
};
