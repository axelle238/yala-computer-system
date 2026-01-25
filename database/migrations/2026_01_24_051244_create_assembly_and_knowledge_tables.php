<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. PC Assembly Workflow
        Schema::create('pc_assemblies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('build_name')->nullable(); // e.g. "Gaming Beast RTX 4090"
            $table->enum('status', ['queued', 'picking', 'building', 'testing', 'completed', 'cancelled'])->default('queued');

            $table->text('specs_snapshot')->nullable(); // JSON summary of parts
            $table->text('technician_notes')->nullable();
            $table->string('benchmark_score')->nullable(); // e.g. "Cinebench: 15000"

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // 2. Internal Knowledge Base (Wiki/SOP)
        Schema::create('knowledge_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category'); // e.g., 'SOP Kasir', 'Troubleshooting', 'HR Rules'
            $table->longText('content');
            $table->foreignId('author_id')->constrained('users');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_articles');
        Schema::dropIfExists('pc_assemblies');
    }
};
