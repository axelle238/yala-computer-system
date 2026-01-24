<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jika tabel roles belum ada (cek conflict dengan migrasi lama)
        if (!Schema::hasTable('roles_v2')) {
            Schema::create('roles_v2', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique(); // e.g., 'Super Admin', 'Warehouse Staff'
                $table->string('slug')->unique(); // e.g., 'super-admin', 'warehouse-staff'
                $table->timestamps();
            });
        }

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'product.view'
            $table->string('group')->nullable(); // e.g., 'Product'
            $table->timestamps();
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles_v2')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->primary(['role_id', 'permission_id']);
        });

        // Add role_id to users table (replacing old enum string column eventually)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_v2_id')->nullable()->after('role')->constrained('roles_v2')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_v2_id']);
            $table->dropColumn('role_v2_id');
        });
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles_v2');
    }
};