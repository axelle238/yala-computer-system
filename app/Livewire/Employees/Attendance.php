<?php

namespace App\Livewire\Employees;

use App\Models\Attendance as AttendanceModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
#[Title('Absensi Harian')]
class Attendance extends Component
{
    use WithPagination;

    public $todayAttendance;
    public $currentTime;

    public function mount()
    {
        $this->refreshToday();
        $this->currentTime = now()->format('H:i');
    }

    public function refreshToday()
    {
        $this->todayAttendance = AttendanceModel::where('user_id', Auth::id())
            ->where('date', date('Y-m-d'))
            ->first();
    }

    public function clockIn()
    {
        if ($this->todayAttendance) return;

        $now = Carbon::now();
        
        // Logika Keterlambatan (Misal masuk jam 09:00)
        $status = 'present';
        $limit = Carbon::createFromTime(9, 15, 0); // Toleransi sampai 09:15
        $lateMinutes = 0;

        if ($now->gt($limit)) {
            $status = 'late';
            $lateMinutes = $limit->diffInMinutes($now);
        }

        AttendanceModel::create([
            'user_id' => Auth::id(),
            'date' => date('Y-m-d'),
            'clock_in' => $now->toTimeString(),
            'status' => $status,
            'late_minutes' => $lateMinutes,
            'ip_address' => request()->ip(),
        ]);

        session()->flash('success', 'Berhasil Absen Masuk! Semangat bekerja.');
        $this->refreshToday();
    }

    public function clockOut()
    {
        if (!$this->todayAttendance) return;

        $this->todayAttendance->update([
            'clock_out' => Carbon::now()->toTimeString(),
        ]);

        session()->flash('success', 'Berhasil Absen Pulang. Hati-hati di jalan!');
        $this->refreshToday();
    }

    public function render()
    {
        $history = AttendanceModel::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('livewire.employees.attendance', [
            'history' => $history
        ]);
    }
}
