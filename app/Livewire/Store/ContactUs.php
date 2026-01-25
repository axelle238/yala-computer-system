<?php

namespace App\Livewire\Store;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Hubungi Kami - Yala Computer')]
class ContactUs extends Component
{
    public $name;
    public $email;
    public $subject;
    public $message;

    public function submit()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'subject' => 'required|min:5',
            'message' => 'required|min:10',
        ]);

        ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        $this->reset();
        $this->dispatch('notify', message: 'Pesan terkirim! Kami akan segera menghubungi Anda.', type: 'success');
    }

    public function render()
    {
        return view('livewire.store.contact-us');
    }
}
