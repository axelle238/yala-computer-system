<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        Shift::create(['name' => 'Pagi', 'start_time' => '08:00', 'end_time' => '16:00']);
        Shift::create(['name' => 'Siang', 'start_time' => '14:00', 'end_time' => '22:00']);
        Shift::create(['name' => 'Malam', 'start_time' => '22:00', 'end_time' => '06:00']);
        Shift::create(['name' => 'Off', 'start_time' => '00:00', 'end_time' => '00:00']);
    }
}
