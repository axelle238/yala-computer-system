<?php

namespace App\Livewire\Shift;

use App\Models\EmployeeShift;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Jadwal Shift Kerja - Yala Computer')]
class Manager extends Component
{
    public $startOfWeek;
    public $dates = [];
    
    // State
    public $selectedShiftId;

    public function mount()
    {
        $this->startOfWeek = now()->startOfWeek();
        $this->generateDates();
        
        $firstShift = Shift::first();
        if ($firstShift) {
            $this->selectedShiftId = $firstShift->id;
        }
    }

    public function nextWeek()
    {
        $this->startOfWeek->addWeek();
        $this->generateDates();
    }

    public function prevWeek()
    {
        $this->startOfWeek->subWeek();
        $this->generateDates();
    }

    public function generateDates()
    {
        $this->dates = [];
        for ($i = 0; $i < 7; $i++) {
            $this->dates[] = $this->startOfWeek->copy()->addDays($i);
        }
    }

    public function assignShift($userId, $dateStr)
    {
        $date = Carbon::parse($dateStr);
        
        // Remove existing
        EmployeeShift::where('user_id', $userId)->where('date', $date)->delete();

        // Add new if not 'Off' (assuming Off is just deleting or specific ID)
        // Let's assume we always create record
        EmployeeShift::create([
            'user_id' => $userId,
            'shift_id' => $this->selectedShiftId,
            'date' => $date
        ]);

        $this->dispatch('notify', message: 'Jadwal diperbarui.');
    }

    public function render()
    {
        $users = User::whereIn('role', ['technician', 'sales', 'staff'])->get();
        $shifts = Shift::all();

        // Eager load schedules
        $schedules = EmployeeShift::whereIn('user_id', $users->pluck('id'))
            ->whereBetween('date', [$this->dates[0], end($this->dates)])
            ->get()
            ->groupBy('user_id');

        return view('livewire.shift.manager', [
            'users' => $users,
            'shifts' => $shifts,
            'schedules' => $schedules
        ]);
    }
}