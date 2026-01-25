<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Shift Management (Handle existing table)
        if (! Schema::hasTable('shifts')) {
            Schema::create('shifts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->time('start_time');
                $table->time('end_time');
                $table->integer('late_tolerance')->default(15);
                $table->json('work_days')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            Schema::table('shifts', function (Blueprint $table) {
                if (! Schema::hasColumn('shifts', 'late_tolerance')) {
                    $table->integer('late_tolerance')->default(15);
                }
                if (! Schema::hasColumn('shifts', 'work_days')) {
                    $table->json('work_days')->nullable();
                }
            });
        }

        // 2. Leave Management (Cuti/Izin)
        if (! Schema::hasTable('leave_requests')) {
            Schema::create('leave_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->enum('type', ['annual', 'sick', 'unpaid', 'other'])->default('annual');
                $table->date('start_date');
                $table->date('end_date');
                $table->integer('total_days');
                $table->text('reason');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->foreignId('approved_by')->nullable()->constrained('users');
                $table->timestamps();
            });
        }

        // 3. Update Attendances
        Schema::table('attendances', function (Blueprint $table) {
            if (! Schema::hasColumn('attendances', 'shift_id')) {
                $table->foreignId('shift_id')->nullable()->constrained();
            }
            if (! Schema::hasColumn('attendances', 'late_minutes')) {
                $table->integer('late_minutes')->default(0);
            }
            if (! Schema::hasColumn('attendances', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
            }
            if (! Schema::hasColumn('attendances', 'photo_path')) {
                $table->string('photo_path')->nullable();
            }
        });

        // 4. Update Users (Default Shift)
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'shift_id')) {
                $table->foreignId('shift_id')->nullable()->constrained();
            }
            if (! Schema::hasColumn('users', 'transport_allowance')) {
                $table->decimal('transport_allowance', 15, 2)->default(0);
            }
            if (! Schema::hasColumn('users', 'meal_allowance')) {
                $table->decimal('meal_allowance', 15, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        // Safe down
    }
};
