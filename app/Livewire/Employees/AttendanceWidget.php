<?php

namespace App\Livewire\Employees;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AttendanceWidget extends Component
{
    public $todayAttendance;

    public $currentTime;

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $this->todayAttendance = Attendance::where('user_id', Auth::id())
            ->where('date', date('Y-m-d'))
            ->first();
        $this->currentTime = now()->format('H:i');
    }

    public function clockIn()
    {
        if ($this->todayAttendance) {
            return;
        }

        $user = Auth::user();

        // 1. Determine Shift
        // Check dynamic schedule first
        $schedule = \App\Models\EmployeeShift::where('user_id', $user->id)
            ->where('date', date('Y-m-d'))
            ->with('shift')
            ->first();

        $shift = $schedule ? $schedule->shift : $user->shift;

        // Default if no shift assigned: 09:00 - 17:00
        $startTime = $shift ? Carbon::parse($shift->start_time) : Carbon::parse('09:00:00');
        $lateTolerance = $shift ? $shift->late_tolerance : 15;

        // 2. Calculate Late
        $clockInTime = now();
        // Create Carbon instance for shift start on TODAY
        $shiftStartDateTime = Carbon::parse(date('Y-m-d').' '.$startTime->format('H:i:s'));

        $lateMinutes = 0;
        $status = 'present';

        if ($clockInTime->gt($shiftStartDateTime->addMinutes($lateTolerance))) {
            $status = 'late';
            // Recalculate late minutes from original start time (without tolerance buffer usually, or with? let's count from start time)
            $lateMinutes = $clockInTime->diffInMinutes($shiftStartDateTime->subMinutes($lateTolerance));
        }

        Attendance::create([
            'user_id' => $user->id,
            'shift_id' => $shift ? $shift->id : null,
            'date' => date('Y-m-d'),
            'clock_in' => $clockInTime,
            'status' => $status,
            'late_minutes' => $lateMinutes,
            'ip_address' => request()->ip(),
        ]);

        $this->dispatch('notify', message: 'Berhasil Clock In! Selamat bekerja.', type: 'success');
        $this->refreshData();
    }

    public function clockOut()
    {
        if (! $this->todayAttendance || $this->todayAttendance->clock_out) {
            return;
        }

        $clockOutTime = now();
        $this->todayAttendance->update([
            'clock_out' => $clockOutTime,
        ]);

        // Calculate Overtime
        // Needs Shift End Time
        $shift = $this->todayAttendance->shift;
        if ($shift) {
            $shiftEndDateTime = Carbon::parse(date('Y-m-d').' '.$shift->end_time);

            if ($clockOutTime->gt($shiftEndDateTime)) {
                $overtimeMinutes = $clockOutTime->diffInMinutes($shiftEndDateTime);
                $overtimeHours = round($overtimeMinutes / 60, 2); // Decimal hours

                // Only count significant overtime (e.g. > 30 mins)
                if ($overtimeMinutes > 30) {
                    $this->todayAttendance->update(['overtime_hours' => $overtimeHours]);
                }
            }
        }

        $this->dispatch('notify', message: 'Berhasil Clock Out. Hati-hati di jalan!', type: 'success');
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.employees.attendance-widget');
    }
}
