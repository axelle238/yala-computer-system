<?php

namespace App\Livewire\Employees;

use App\Models\Attendance as AttendanceModel;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Absensi Karyawan - Yala Computer')]
class Attendance extends Component
{
    use WithPagination;

    public $dateFilter;
    public $search = '';

    public function mount()
    {
        $this->dateFilter = date('Y-m-d');
    }

    public function render()
    {
        $query = AttendanceModel::with('user')
            ->whereDate('date', $this->dateFilter);

        if ($this->search) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $attendances = $query->orderBy('clock_in')->paginate(20);

        // Statistics for the selected day
        $totalEmployees = User::where('role', '!=', 'customer')->count();
        $presentCount = AttendanceModel::whereDate('date', $this->dateFilter)->where('status', 'present')->count();
        $lateCount = AttendanceModel::whereDate('date', $this->dateFilter)->where('status', 'late')->count();
        $absentCount = $totalEmployees - ($presentCount + $lateCount); // Rough estimate

        return view('livewire.employees.attendance', [
            'attendances' => $attendances,
            'presentCount' => $presentCount,
            'lateCount' => $lateCount,
            'absentCount' => max(0, $absentCount),
        ]);
    }
}
