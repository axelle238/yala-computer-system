<?php

namespace App\Livewire\Member;

use App\Models\LoyaltyLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Loyalty Points & Referral - Member Area')]
class LoyaltyPoints extends Component
{
    public function mount()
    {
        if (!Auth::user()->referral_code) {
            Auth::user()->update([
                'referral_code' => strtoupper(Str::random(8))
            ]);
        }
    }

    public function render()
    {
        $logs = LoyaltyLog::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.member.loyalty-points', [
            'logs' => $logs,
            'user' => Auth::user()
        ]);
    }
}