<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if table exists to avoid errors on fresh run vs modify
        if (! Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('excerpt')->nullable();
                $table->longText('content');
                $table->string('image_path')->nullable();
                $table->string('category')->default('General'); // New: Category
                $table->json('tags')->nullable(); // New: Tags (JSON)
                $table->string('author_name')->nullable(); // New: Author
                $table->boolean('is_featured')->default(false); // New: Featured
                $table->unsignedBigInteger('views_count')->default(0); // New: Views
                $table->boolean('is_published')->default(true);
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
            });
        } else {
            // Add columns if table exists
            Schema::table('articles', function (Blueprint $table) {
                if (! Schema::hasColumn('articles', 'category')) {
                    $table->string('category')->default('General');
                }
                if (! Schema::hasColumn('articles', 'tags')) {
                    $table->json('tags')->nullable();
                }
                if (! Schema::hasColumn('articles', 'author_name')) {
                    $table->string('author_name')->nullable();
                }
                if (! Schema::hasColumn('articles', 'is_featured')) {
                    $table->boolean('is_featured')->default(false);
                }
                if (! Schema::hasColumn('articles', 'views_count')) {
                    $table->unsignedBigInteger('views_count')->default(0);
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
