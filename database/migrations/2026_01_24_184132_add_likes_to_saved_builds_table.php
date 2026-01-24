<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('saved_builds', 'is_public')) {
            Schema::table('saved_builds', function (Blueprint $table) {
                $table->boolean('is_public')->default(false);
                $table->integer('likes_count')->default(0);
                $table->integer('views_count')->default(0);
            });
        }

        if (!Schema::hasTable('build_likes')) {
            Schema::create('build_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('saved_build_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                $table->unique(['saved_build_id', 'user_id']);
            });
        }

        if (!Schema::hasTable('build_comments')) {
            Schema::create('build_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('saved_build_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('comment');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('build_comments');
        Schema::dropIfExists('build_likes');
        Schema::table('saved_builds', function (Blueprint $table) {
            $table->dropColumn(['is_public', 'likes_count', 'views_count']);
        });
    }
};