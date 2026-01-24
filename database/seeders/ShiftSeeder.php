<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    public function run()
    {
        Shift::firstOrCreate([
            'name' => 'Regular Office',
        ], [
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'late_tolerance' => 15,
            'work_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
            'is_active' => true
        ]);

        Shift::firstOrCreate([
            'name' => 'Technician Shift',
        ], [
            'start_time' => '10:00:00',
            'end_time' => '19:00:00',
            'late_tolerance' => 10,
            'work_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            'is_active' => true
        ]);
    }
}