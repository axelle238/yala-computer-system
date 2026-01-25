<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Shifts (Jadwal Kerja)
        if (! Schema::hasTable('shifts')) {
            Schema::create('shifts', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Pagi, Siang, Malam
                $table->time('start_time');
                $table->time('end_time');
                $table->timestamps();
            });
        }

        // 2. Employee Shifts (Assignment)
        if (! Schema::hasTable('employee_shifts')) {
            Schema::create('employee_shifts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('shift_id')->constrained();
                $table->date('date');
                $table->enum('status', ['scheduled', 'present', 'absent', 'leave'])->default('scheduled');
                $table->timestamps();
            });
        }

        // 3. Attendances (Absensi Real)
        if (! Schema::hasTable('attendances')) {
            Schema::create('attendances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->foreignId('shift_id')->nullable()->constrained();
                $table->timestamp('clock_in')->nullable();
                $table->timestamp('clock_out')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('location_lat_long')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 4. Payrolls (Penggajian)
        if (! Schema::hasTable('payrolls')) {
            Schema::create('payrolls', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->string('period_month'); // 2026-01
                $table->decimal('basic_salary', 15, 2);
                $table->decimal('total_allowance', 15, 2)->default(0); // Tunjangan
                $table->decimal('total_commission', 15, 2)->default(0); // Dari sales/service
                $table->decimal('total_deduction', 15, 2)->default(0); // Potongan
                $table->decimal('net_salary', 15, 2);
                $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
                $table->date('paid_at')->nullable();
                $table->timestamps();
            });
        }

        // 5. Commissions (Komisi per Transaksi - Link ke Payroll)
        // Note: Commission model might exist, creating table if not.
        if (! Schema::hasTable('commissions')) {
            Schema::create('commissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->decimal('amount', 15, 2);
                $table->string('description'); // "Sales #1001"
                $table->morphs('source'); // Order or ServiceTicket
                $table->boolean('is_paid')->default(false); // Link to payroll
                $table->foreignId('payroll_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamps();
            });
        } else {
            // Add payroll_id if table exists but column doesn't
            if (! Schema::hasColumn('commissions', 'payroll_id')) {
                Schema::table('commissions', function (Blueprint $table) {
                    $table->foreignId('payroll_id')->nullable()->constrained()->onDelete('set null');
                });
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('employee_shifts');
        Schema::dropIfExists('shifts');
    }
};
