<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('total_allowance', 15, 2)->default(0)->after('base_salary');
            $table->decimal('overtime_pay', 15, 2)->default(0)->after('total_allowance');
            $table->json('details')->nullable()->after('net_salary'); // Snapshot of calculations
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['total_allowance', 'overtime_pay', 'details']);
        });
    }
};