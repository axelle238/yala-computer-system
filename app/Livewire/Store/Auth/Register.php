<?php

namespace App\Livewire\Store\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Daftar Akun Baru - Yala Computer')]
class Register extends Component
{
    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    #[Url(as: 'ref')]
    public $referralCode = '';

    public function register()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $referrerId = null;
        if ($this->referralCode) {
            $referrer = User::where('referral_code', $this->referralCode)->first();
            if ($referrer) {
                $referrerId = $referrer->id;
            }
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'customer',
            'referrer_id' => $referrerId,
        ]);

        Auth::login($user);

        return redirect()->route('beranda');
    }

    public function render()
    {
        return view('livewire.store.auth.register');
    }
}
