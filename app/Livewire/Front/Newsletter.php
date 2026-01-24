<?php

namespace App\Livewire\Front;

use App\Models\Subscriber;
use Livewire\Component;

class Newsletter extends Component
{
    public $email = '';

    public function subscribe()
    {
        $this->validate(['email' => 'required|email']);

        if (Subscriber::where('email', $this->email)->exists()) {
            $this->dispatch('notify', message: 'Email sudah terdaftar!', type: 'info');
            return;
        }

        Subscriber::create(['email' => $this->email]);
        $this->email = '';
        $this->dispatch('notify', message: 'Berhasil berlangganan newsletter!', type: 'success');
    }

    public function render()
    {
        return view('livewire.front.newsletter');
    }
}
