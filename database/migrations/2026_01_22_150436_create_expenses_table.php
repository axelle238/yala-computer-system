<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Listrik, Air, Wifi
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->string('category')->default('operational'); // operational, marketing, maintenance
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained(); // Siapa yang input
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};