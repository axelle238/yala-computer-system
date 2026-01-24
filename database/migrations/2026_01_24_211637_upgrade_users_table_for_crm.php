<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'loyalty_points')) {
                $table->integer('loyalty_points')->default(0)->after('email');
            }
            if (!Schema::hasColumn('users', 'loyalty_tier')) {
                $table->enum('loyalty_tier', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze')->after('loyalty_points');
            }
            if (!Schema::hasColumn('users', 'total_spent')) {
                $table->decimal('total_spent', 15, 2)->default(0)->after('loyalty_tier');
            }
            if (!Schema::hasColumn('users', 'last_purchase_at')) {
                $table->timestamp('last_purchase_at')->nullable()->after('total_spent');
            }
            if (!Schema::hasColumn('users', 'notes')) {
                $table->text('notes')->nullable(); // Catatan internal admin tentang customer ini
            }
        });

        // Tabel Riwayat Poin (Jika belum ada)
        if (!Schema::hasTable('point_histories')) {
            Schema::create('point_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->integer('amount'); // Positif (Dapat) atau Negatif (Pakai)
                $table->string('type'); // purchase, redemption, bonus, adjustment
                $table->string('reference_id')->nullable(); // ID Order / Tiket
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('point_histories');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['loyalty_points', 'loyalty_tier', 'total_spent', 'last_purchase_at', 'notes']);
        });
    }
};