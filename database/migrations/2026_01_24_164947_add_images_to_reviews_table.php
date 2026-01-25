<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (! Schema::hasColumn('reviews', 'images')) {
                $table->text('images')->nullable()->after('comment');
            }
            if (! Schema::hasColumn('reviews', 'reviewer_name')) {
                $table->string('reviewer_name')->nullable()->after('user_id'); // Snapshot name
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['images', 'reviewer_name']);
        });
    }
};
