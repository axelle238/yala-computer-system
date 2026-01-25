<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Settings Table - SKIP (Already exists)

        // 2. Commissions Logic
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('description'); // e.g., "Komisi Service #SRV-001"

            // Polymorphic relation to link to Order or ServiceTicket
            $table->nullableMorphs('source');

            $table->boolean('is_paid')->default(false); // True if included in a generated payroll
            $table->timestamps();
        });

        // 3. Payrolls Table
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('period_month'); // "01-2026"
            $table->date('pay_date');

            $table->decimal('base_salary', 15, 2)->default(0);
            $table->decimal('total_commission', 15, 2)->default(0);
            $table->decimal('deductions', 15, 2)->default(0); // Kasbon etc
            $table->decimal('net_salary', 15, 2); // Base + Comm - Deduct

            $table->enum('status', ['draft', 'paid'])->default('draft');
            $table->timestamps();
        });

        // 4. Update Users (Base Salary) - Check first
        if (! Schema::hasColumn('users', 'base_salary')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('base_salary', 15, 2)->default(0)->after('email');
            });
        }

        if (! Schema::hasColumn('users', 'job_title')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('job_title')->nullable()->after('email');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('commissions');
    }
};
