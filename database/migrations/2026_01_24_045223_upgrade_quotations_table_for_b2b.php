<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Check columns to avoid duplication if migration re-runs
            if (! Schema::hasColumn('quotations', 'quotation_number')) {
                $table->string('quotation_number')->unique()->after('id');
            }
            if (! Schema::hasColumn('quotations', 'valid_until')) {
                $table->date('valid_until')->nullable()->after('status');
            }
            if (! Schema::hasColumn('quotations', 'terms_and_conditions')) {
                $table->text('terms_and_conditions')->nullable()->after('valid_until');
            }
            if (! Schema::hasColumn('quotations', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            }
            if (! Schema::hasColumn('quotations', 'converted_order_id')) {
                $table->foreignId('converted_order_id')->nullable()->after('approval_status')->constrained('orders')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign(['converted_order_id']);
            $table->dropColumn(['quotation_number', 'valid_until', 'terms_and_conditions', 'approval_status', 'converted_order_id']);
        });
    }
};
