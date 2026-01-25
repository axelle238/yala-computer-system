<?php

namespace App\Livewire\Member;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Program Referral & Cuan')]
class Referrals extends Component
{
    public $referralCode;
    public $referralLink;
    public $totalReferrals;
    public $totalEarnings;

    public function mount()
    {
        $user = Auth::user();
        // Generate code if missing (Temporary logic, should be in User Observer)
        if (!$user->referral_code) {
            $user->referral_code = 'YALA' . $user->id . strtoupper(\Illuminate\Support\Str::random(3));
            $user->save();
        }

        $this->referralCode = $user->referral_code;
        $this->referralLink = route('customer.register', ['ref' => $this->referralCode]);
        
        // Count Referrals
        $this->totalReferrals = User::where('referrer_id', $user->id)->count();
        $this->totalEarnings = $this->totalReferrals * 5000; // Mock earning 5000 points per referral
    }

    public function copyLink()
    {
        $this->dispatch('notify', message: 'Link referral disalin ke clipboard!', type: 'success');
    }

    public function render()
    {
        $referrals = User::where('referrer_id', Auth::id())
            ->latest()
            ->paginate(5);

        return view('livewire.member.referrals', ['referrals' => $referrals]);
    }
}