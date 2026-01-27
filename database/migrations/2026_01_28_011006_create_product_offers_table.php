<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->string('brand')->nullable();
            $table->enum('condition', ['new', 'used_like_new', 'used_good', 'used_fair'])->default('used_good');
            $table->decimal('expected_price', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'accepted', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_offers');
    }
};
