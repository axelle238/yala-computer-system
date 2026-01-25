<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rma_items', function (Blueprint $table) {
            if (! Schema::hasColumn('rma_items', 'evidence_files')) {
                $table->json('evidence_files')->nullable()->after('problem_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rma_items', function (Blueprint $table) {
            $table->dropColumn('evidence_files');
        });
    }
};
