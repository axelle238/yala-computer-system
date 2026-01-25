<?php

namespace App\Livewire\Shift;

use App\Models\EmployeeShift;
use App\Models\Shift; // Asumsi: id, name, start_time, end_time, color
use App\Models\User; // Asumsi: user_id, shift_id, date
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Manajemen Jadwal Shift')]
class Manager extends Component
{
    public $startDate; // Senin minggu ini

    public $weekDates = [];

    public $employees = [];

    public $shifts = [];

    // View State
    public $activeAction = null; // null, 'edit'

    public $selectedUserId;

    public $selectedDate;

    public $selectedShiftId;

    public function mount()
    {
        $this->startDate = now()->startOfWeek()->format('Y-m-d');
        $this->loadData();
    }

    public function prevWeek()
    {
        $this->startDate = Carbon::parse($this->startDate)->subWeek()->format('Y-m-d');
        $this->loadData();
    }

    public function nextWeek()
    {
        $this->startDate = Carbon::parse($this->startDate)->addWeek()->format('Y-m-d');
        $this->loadData();
    }

    public function loadData()
    {
        // 1. Generate Week Dates Headers
        $this->weekDates = [];
        $start = Carbon::parse($this->startDate);
        for ($i = 0; $i < 7; $i++) {
            $this->weekDates[] = $start->copy()->addDays($i);
        }

        // 2. Load Master Shifts (Mock if empty)
        // In real app: $this->shifts = Shift::all();
        $this->shifts = collect([
            ['id' => 1, 'name' => 'Pagi', 'time' => '08:00 - 16:00', 'color' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
            ['id' => 2, 'name' => 'Siang', 'time' => '14:00 - 22:00', 'color' => 'bg-blue-100 text-blue-700 border-blue-200'],
            ['id' => 3, 'name' => 'Malam', 'time' => '18:00 - 02:00', 'color' => 'bg-indigo-100 text-indigo-700 border-indigo-200'],
            ['id' => 4, 'name' => 'Libur', 'time' => 'OFF', 'color' => 'bg-slate-100 text-slate-500 border-slate-200'],
        ]);

        // 3. Load Employees & Their Shifts
        $this->employees = User::whereIn('role', ['technician', 'cashier', 'warehouse'])
            ->get()
            ->map(function ($user) {
                // Mocking shift data attachment. In real app, use relation: $user->shifts
                $user->roster = [];
                foreach ($this->weekDates as $date) {
                    $dateStr = $date->format('Y-m-d');
                    // Randomize mock data for demo visual
                    $user->roster[$dateStr] = session()->get("roster_{$user->id}_{$dateStr}", 4); // Default OFF
                }

                return $user;
            });
    }

    public function openShiftPanel($userId, $date)
    {
        $this->selectedUserId = $userId;
        $this->selectedDate = $date;
        $this->selectedShiftId = session()->get("roster_{$userId}_{$date}", 4);
        $this->activeAction = 'edit';
    }

    public function closePanel()
    {
        $this->reset(['activeAction', 'selectedUserId', 'selectedDate', 'selectedShiftId']);
    }

    public function saveShift()
    {
        // In real app: EmployeeShift::updateOrCreate(...)
        session()->put("roster_{$this->selectedUserId}_{$this->selectedDate}", $this->selectedShiftId);

        $this->closePanel();
        $this->loadData(); // Refresh UI
        $this->dispatch('notify', message: 'Jadwal berhasil diperbarui.', type: 'success');
    }

    public function render()
    {
        return view('livewire.shift.manager');
    }
}
