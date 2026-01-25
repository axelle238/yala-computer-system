<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update Saved Builds
        Schema::table('saved_builds', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('share_token');
            $table->integer('likes_count')->default(0)->after('is_public');
            $table->integer('views_count')->default(0)->after('likes_count');
        });

        // Likes Table
        Schema::create('build_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('saved_build_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'saved_build_id']); // One like per user per build
        });

        // Comments Table
        Schema::create('build_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('saved_build_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });
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
