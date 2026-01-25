<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->longText('content');
                $table->string('image_path')->nullable();
                $table->foreignId('author_id')->constrained('users');
                $table->boolean('is_published')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->string('tags')->nullable(); // JSON or comma separated
                $table->integer('views_count')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
