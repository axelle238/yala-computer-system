<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Change enum to string to allow 'customer' and future roles easily
            $table->string('role')->default('customer')->change();
        });
    }

    public function down(): void
    {
        // Revert is risky if we have customers, but strictly speaking we'd go back to enum
        // For this project scope, keeping it as string is fine.
    }
};