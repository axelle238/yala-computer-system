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

    public function sendMessage()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        $this->reset();
        $this->dispatch('notify', message: 'Pesan Anda berhasil dikirim. Kami akan segera membalasnya!', type: 'success');
    }

    public function render()
    {
        return view('livewire.store.contact-us');
    }
}