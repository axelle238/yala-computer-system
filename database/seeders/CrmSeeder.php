<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\PointHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CrmSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'sultan@gmail.com'],
            [
                'name' => 'Sultan Gaming',
                'phone' => '089988776655',
                'password' => bcrypt('password'),
                'loyalty_points' => 5000,
                'loyalty_tier' => 'gold',
                'total_spent' => 25000000,
                'last_purchase_at' => now(),
                'notes' => 'Sering beli part High-End. Suka diskon.',
            ]
        );

        // History Belanja (Check if exists first to avoid spamming on re-seed)
        if ($user->orders()->count() == 0) {
            for ($i = 0; $i < 5; $i++) {
                Order::create([
                    'order_number' => 'ORD-CRM-' . rand(1000,9999),
                    'user_id' => $user->id,
                    'total_amount' => 5000000,
                    'status' => 'completed',
                    'created_at' => Carbon::now()->subMonths($i),
                ]);
            }
        }

        // History Poin
        if ($user->pointHistories()->count() == 0) {
            PointHistory::create([
                'user_id' => $user->id,
                'amount' => 5000,
                'type' => 'earned', 
                'description' => 'Bonus Pembelian PC Rakitan',
            ]);
        }
    }
}
