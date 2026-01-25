<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_assets', function (Blueprint $table) {
            if (! Schema::hasColumn('company_assets', 'category')) {
                $table->string('category')->default('General')->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_assets', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
