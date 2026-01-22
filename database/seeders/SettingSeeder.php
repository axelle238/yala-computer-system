<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'shop_name',
                'value' => 'Yala Computer',
                'label' => 'Nama Toko',
                'type' => 'text'
            ],
            [
                'key' => 'shop_address',
                'value' => 'Jl. Teknologi No. 88, Jakarta Selatan',
                'label' => 'Alamat Toko',
                'type' => 'textarea'
            ],
            [
                'key' => 'whatsapp_number',
                'value' => '6281234567890',
                'label' => 'Nomor WhatsApp Admin (Format: 628...)',
                'type' => 'number'
            ],
            [
                'key' => 'shop_description',
                'value' => 'Pusat belanja hardware dan rakit PC terpercaya.',
                'label' => 'Deskripsi Singkat',
                'type' => 'textarea'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
