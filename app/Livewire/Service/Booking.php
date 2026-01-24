<?php

namespace App\Livewire\Service;

use App\Models\ServiceAppointment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Booking Service - Yala Computer')]
class Booking extends Component
{
    public $date;
    public $time;
    public $device_type = 'Laptop';
    public $problem_description;

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function book()
    {
        $this->validate([
            'date' => 'required|date|after:today',
            'time' => 'required',
            'device_type' => 'required',
            'problem_description' => 'required|string|min:10',
        ]);

        ServiceAppointment::create([
            'user_id' => Auth::id(),
            'appointment_date' => $this->date,
            'appointment_time' => $this->time,
            'device_type' => $this->device_type,
            'problem_description' => $this->problem_description,
            'status' => 'scheduled'
        ]);

        session()->flash('success', 'Jadwal service berhasil dibuat! Silakan datang tepat waktu.');
        return redirect()->route('member.dashboard');
    }

    public function render()
    {
        return view('livewire.service.booking');
    }
}
