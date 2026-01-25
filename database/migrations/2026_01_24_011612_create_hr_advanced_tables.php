<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Master Shift
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Pagi, Siang
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });

        // Employee Schedule
        Schema::create('employee_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->timestamps();
            $table->unique(['user_id', 'date']);
        });

        // Reimbursements
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->id();
            $table->string('claim_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('category'); // Transport, Meal, Medical
            $table->decimal('amount', 15, 2);
            $table->text('description');
            $table->string('proof_file')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, paid
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reimbursements');
        Schema::dropIfExists('employee_shifts');
        Schema::dropIfExists('shifts');
    }
};
