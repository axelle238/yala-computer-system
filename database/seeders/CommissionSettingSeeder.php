<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class CommissionSettingSeeder extends Seeder
{
    public function run(): void
    {
        // 10% Komisi Service untuk Teknisi
        Setting::updateOrCreate(['key' => 'commission_service_percent'], ['value' => '10']);

        // 1% Komisi Penjualan untuk Sales/Kasir
        Setting::updateOrCreate(['key' => 'commission_sales_percent'], ['value' => '1']);
    }
}
