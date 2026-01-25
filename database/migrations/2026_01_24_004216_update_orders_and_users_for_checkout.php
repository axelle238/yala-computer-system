<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('points')->default(0)->after('email');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->text('shipping_address')->nullable()->after('notes');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_courier')->nullable()->after('shipping_city');
            $table->decimal('shipping_cost', 15, 2)->default(0)->after('shipping_courier');
            $table->integer('points_redeemed')->default(0)->after('shipping_cost');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('points_redeemed');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_address', 'shipping_city', 'shipping_courier', 'shipping_cost', 'points_redeemed', 'discount_amount']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('points');
        });
    }
};
