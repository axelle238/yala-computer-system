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

        $subscriber = Subscriber::firstOrCreate(
            ['email' => $this->email],
            ['is_active' => true]
        );

        if (! $subscriber->wasRecentlyCreated && ! $subscriber->is_active) {
            $subscriber->update(['is_active' => true]);
        }

        $this->email = '';
        $this->dispatch('notify', message: 'Terima kasih telah berlangganan newsletter kami!', type: 'success');
    }

    public function render()
    {
        return view('livewire.front.newsletter');
    }
}
