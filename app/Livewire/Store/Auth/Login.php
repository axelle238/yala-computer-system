<?php

namespace App\Livewire\Store\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Login Pelanggan - Yala Computer')]
class Login extends Component
{
    public $email = '';

    public $password = '';

    public $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            return redirect()->intended(route('home'));
        }

        $this->addError('email', 'Kredensial tidak cocok.');
    }

    public function render()
    {
        return view('livewire.store.auth.login');
    }
}
