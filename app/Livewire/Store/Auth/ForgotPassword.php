<?php

namespace App\Livewire\Store\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.auth')]
#[Title('Lupa Password - Yala Computer')]
class ForgotPassword extends Component
{
    public $email;
    public $status;

    public function sendResetLink()
    {
        $this->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->status = __($status);
            $this->dispatch('notify', message: 'Link reset password telah dikirim ke email Anda.', type: 'success');
        } else {
            $this->addError('email', __($status));
        }
    }

    public function render()
    {
        return view('livewire.store.auth.forgot-password');
    }
}
