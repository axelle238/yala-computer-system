<?php

namespace App\Livewire\Member;

use App\Models\ServiceTicket;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Status Servis - Yala Computer')]
class Services extends Component
{
    public function render()
    {
        return view('livewire.member.services', [
            'services' => ServiceTicket::where('customer_phone', Auth::user()->phone)->latest()->get(),
        ]);
    }
}
