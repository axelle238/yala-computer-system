<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saved_builds', function (Blueprint $table) {
            if (! Schema::hasColumn('saved_builds', 'is_public')) {
                $table->boolean('is_public')->default(false)->after('share_token');
            }
            if (! Schema::hasColumn('saved_builds', 'likes_count')) {
                $table->integer('likes_count')->default(0)->after('is_public');
            }
        });

        // Like tracking
        if (! Schema::hasTable('build_likes')) {
            Schema::create('build_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('saved_build_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['user_id', 'saved_build_id']);
            });
        }
    }

    public function down(): void
    {
        // ...
    }
};
