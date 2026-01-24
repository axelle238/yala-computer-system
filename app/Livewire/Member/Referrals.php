<?php

namespace App\Livewire\Member;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Program Referral - Member Area')]
class Referrals extends Component
{
    public function render()
    {
        $user = Auth::user();
        $referrals = User::where('referred_by', $user->id)->paginate(10);
        
        // Calculate earnings (mock logic, normally from Commission table or Points)
        $totalEarned = $referrals->count() * 50000; // Mock: 50k per referral

        return view('livewire.member.referrals', [
            'referrals' => $referrals,
            'totalEarned' => $totalEarned,
            'referralCode' => $user->referral_code
        ]);
    }
}
