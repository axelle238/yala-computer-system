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
        if ($this->todayAttendance) return;

        // Logic Keterlambatan (Misal jam masuk 09:00)
        $status = 'present';
        if (now()->format('H:i') > '09:15') {
            $status = 'late';
        }

        Attendance::create([
            'user_id' => Auth::id(),
            'date' => date('Y-m-d'),
            'clock_in' => now(),
            'status' => $status,
            'ip_address' => request()->ip()
        ]);

        $this->dispatch('notify', message: 'Berhasil Clock In! Selamat bekerja.', type: 'success');
        $this->refreshData();
    }

    public function clockOut()
    {
        if (!$this->todayAttendance || $this->todayAttendance->clock_out) return;

        $this->todayAttendance->update([
            'clock_out' => now()
        ]);

        $this->dispatch('notify', message: 'Berhasil Clock Out. Hati-hati di jalan!', type: 'success');
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.employees.attendance-widget');
    }
}
