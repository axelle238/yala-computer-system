<?php

namespace App\Livewire\Employees;

use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Absensi Karyawan - Yala Computer')]
class Attendance extends Component
{
    public $todayAttendance;
    public $activeShift;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->todayAttendance = \App\Models\Attendance::where('user_id', Auth::id())
            ->whereDate('created_at', today())
            ->first();
            
        // Simple logic: Assume user has one fixed shift or pick active based on time
        // Ideally should check employee_shifts table
        $this->activeShift = Shift::first(); 
    }

    public function clockIn()
    {
        if ($this->todayAttendance) return;

        \App\Models\Attendance::create([
            'user_id' => Auth::id(),
            'shift_id' => $this->activeShift->id ?? null,
            'clock_in' => now(),
            'ip_address' => request()->ip(),
            'notes' => 'Clock In via System',
        ]);

        $this->dispatch('notify', message: 'Berhasil Clock In!', type: 'success');
        $this->loadData();
    }

    public function clockOut()
    {
        if (!$this->todayAttendance || $this->todayAttendance->clock_out) return;

        $this->todayAttendance->update([
            'clock_out' => now(),
        ]);

        $this->dispatch('notify', message: 'Berhasil Clock Out! Sampai jumpa besok.', type: 'success');
        $this->loadData();
    }

    public function render()
    {
        // History for Admin/HR View
        $history = \App\Models\Attendance::with(['user', 'shift'])
            ->latest()
            ->paginate(10);

        return view('livewire.employees.attendance', [
            'history' => $history
        ]);
    }
}