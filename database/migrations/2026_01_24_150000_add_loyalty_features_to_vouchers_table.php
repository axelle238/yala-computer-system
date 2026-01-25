<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            if (! Schema::hasColumn('vouchers', 'points_cost')) {
                $table->integer('points_cost')->default(0)->after('usage_per_user')->comment('Cost to redeem this voucher');
            }
            if (! Schema::hasColumn('vouchers', 'is_public')) {
                $table->boolean('is_public')->default(false)->after('points_cost')->comment('Show in Redeem Center');
            }
        });

        // Points History Log
        Schema::create('point_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['earned', 'redeemed', 'adjusted'])->default('earned');
            $table->integer('amount'); // +/-
            $table->string('description');
            $table->nullableMorphs('reference'); // Order, VoucherUsage, etc
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_histories');
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['points_cost', 'is_public']);
        });
    }
};
