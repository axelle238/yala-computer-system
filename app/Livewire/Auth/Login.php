<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Login Administrator')]
class Login extends Component
{
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    public $remember = false;

    public function authenticate()
    {
        $this->validate();

        $key = 'login-attempt:'.$this->email.'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('email', "Terlalu banyak percobaan. Silakan coba lagi dalam $seconds detik.");

            return;
        }

        // Rate Limiting handled by middleware usually, but basic check here
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::clear($key);
            session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        RateLimiter::hit($key, 60);

        $this->addError('email', 'Email atau password yang Anda masukkan salah.');
        $this->reset('password');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
