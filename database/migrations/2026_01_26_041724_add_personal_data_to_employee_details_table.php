<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_details', function (Blueprint $table) {
            $table->string('nik', 20)->nullable()->after('user_id');
            $table->string('npwp', 25)->nullable()->after('nik');
            $table->string('phone_number', 20)->nullable()->after('npwp');
            $table->string('place_of_birth')->nullable()->after('phone_number');
            $table->date('date_of_birth')->nullable()->after('place_of_birth');
            $table->text('address')->nullable()->after('date_of_birth');
        });
    }

    public function down(): void
    {
        Schema::table('employee_details', function (Blueprint $table) {
            $table->dropColumn(['nik', 'npwp', 'phone_number', 'place_of_birth', 'date_of_birth', 'address']);
        });
    }
};
