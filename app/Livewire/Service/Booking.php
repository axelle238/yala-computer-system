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
    public $step = 1; // 1: Device Info, 2: Date, 3: Contact (if guest), 4: Confirm

    // Form Data
    public $device_type = 'laptop';
    public $problem_description;
    public $appointment_date;
    public $guest_name;
    public $guest_phone;

    public function mount()
    {
        if (Auth::check()) {
            $this->guest_name = Auth::user()->name;
            $this->guest_phone = Auth::user()->phone;
        }
    }

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'device_type' => 'required',
                'problem_description' => 'required|min:10',
            ]);
        } elseif ($this->step === 2) {
            $this->validate([
                'appointment_date' => 'required|date|after:today',
            ]);
        } elseif ($this->step === 3) {
            $this->validate([
                'guest_name' => 'required',
                'guest_phone' => 'required',
            ]);
        }

        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function submit()
    {
        ServiceAppointment::create([
            'user_id' => Auth::id(),
            'guest_name' => $this->guest_name,
            'guest_phone' => $this->guest_phone,
            'device_type' => $this->device_type,
            'problem_description' => $this->problem_description,
            'appointment_date' => $this->appointment_date,
            'status' => 'scheduled'
        ]);

        $this->dispatch('notify', message: 'Booking berhasil! Kami akan menghubungi Anda.', type: 'success');
        
        // Reset or redirect
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.service.booking');
    }
}