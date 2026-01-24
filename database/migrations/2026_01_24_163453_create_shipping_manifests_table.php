<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_manifests', function (Blueprint $table) {
            $table->id();
            $table->string('manifest_number')->unique(); // MNF-20260124-001
            $table->string('courier_name'); // JNE, J&T, SiCepat
            $table->foreignId('created_by')->constrained('users');
            $table->string('status')->default('draft'); // draft, generated, picked_up
            $table->timestamp('pickup_time')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Add manifest_id to orders table (nullable)
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('shipping_manifest_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_manifest_id']);
            $table->dropColumn('shipping_manifest_id');
        });
        Schema::dropIfExists('shipping_manifests');
    }
};