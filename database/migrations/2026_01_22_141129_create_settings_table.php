<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'shop_name', 'whatsapp_number'
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, number, boolean
            $table->string('label')->nullable(); // Human readable label
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};