<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'store_name', 'value' => 'Yala Computer', 'label' => 'Nama Toko', 'type' => 'text'],
            ['key' => 'store_address', 'value' => 'Jl. Teknologi No. 1, Jakarta', 'label' => 'Alamat Toko', 'type' => 'textarea'],
            ['key' => 'store_phone', 'value' => '0812-3456-7890', 'label' => 'No. Telepon', 'type' => 'text'],
            ['key' => 'tax_rate', 'value' => '11', 'label' => 'Tarif PPN (%)', 'type' => 'number'],
            ['key' => 'receipt_footer', 'value' => 'Terima kasih telah berbelanja!', 'label' => 'Footer Struk', 'type' => 'text'],
            ['key' => 'printer_ip', 'value' => '192.168.1.200', 'label' => 'IP Printer Kasir', 'type' => 'text'],
        ];

        foreach ($defaults as $data) {
            Setting::firstOrCreate(['key' => $data['key']], $data);
        }
    }
}
