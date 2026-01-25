<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\EmployeeDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HrSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setup Admin Employee Detail
        $admin = User::first(); // Assuming ID 1 is Admin
        if ($admin) {
            EmployeeDetail::updateOrCreate(
                ['user_id' => $admin->id],
                [
                    'job_title' => 'Manager Toko',
                    'join_date' => '2020-01-01',
                    'base_salary' => 5000000,
                    'allowance_daily' => 50000,
                    'commission_percentage' => 0, // Manager mungkin dapat profit sharing, bukan komisi per item
                ]
            );
        }

        // 2. Setup Technician
        $tech = User::where('email', 'tech@yala.com')->first();
        if ($tech) {
            EmployeeDetail::updateOrCreate(
                ['user_id' => $tech->id],
                [
                    'job_title' => 'Teknisi Senior',
                    'join_date' => '2022-03-15',
                    'base_salary' => 3500000,
                    'allowance_daily' => 35000,
                    'commission_percentage' => 10, // 10% dari Profit Service
                ]
            );

            // 3. Create Attendance Dummy for Technician (Last 7 days)
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);

                // Skip Sunday
                if ($date->dayOfWeek == Carbon::SUNDAY) {
                    continue;
                }

                $clockIn = $date->copy()->setTime(rand(8, 9), rand(0, 59));
                $clockOut = $date->copy()->setTime(rand(17, 19), rand(0, 30));

                $status = 'present';
                $lateMinutes = 0;

                // Late logic
                if ($clockIn->format('H:i') > '09:15') {
                    $status = 'late';
                    $lateMinutes = rand(5, 60);
                }

                Attendance::create([
                    'user_id' => $tech->id,
                    'date' => $date->toDateString(),
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                ]);
            }
        }
    }
}
