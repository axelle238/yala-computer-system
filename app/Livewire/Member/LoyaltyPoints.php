<?php

namespace App\Livewire\Member;

use App\Models\User;
use App\Models\Voucher; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Tukar Poin - Yala Computer')]
class LoyaltyPoints extends Component
{
    public $userPoints;
    public $rewards = [];
    public $redemptionHistory = [];

    public function mount()
    {
        $this->userPoints = Auth::user()->points ?? 0;
        $this->loadRewards();
        $this->loadHistory();
    }

    public function loadRewards()
    {
        // Mock Rewards Catalog (In real app: Reward::all())
        $this->rewards = collect([
            [
                'id' => 1, 
                'name' => 'Voucher Diskon Rp 50.000', 
                'type' => 'voucher', 
                'value' => 50000, 
                'points' => 5000, 
                'image' => 'voucher-50k.png',
                'desc' => 'Potongan langsung untuk belanja minimal Rp 500.000.'
            ],
            [
                'id' => 2, 
                'name' => 'Gratis Ongkir (JABODETABEK)', 
                'type' => 'voucher', 
                'value' => 20000, 
                'points' => 2500, 
                'image' => 'free-shipping.png',
                'desc' => 'Subsidi ongkir maksimal Rp 20.000.'
            ],
            [
                'id' => 3, 
                'name' => 'Yala Exclusive T-Shirt', 
                'type' => 'product', 
                'value' => 0, 
                'points' => 15000, 
                'image' => 'tshirt.png',
                'desc' => 'Kaos eksklusif komunitas Yala Computer (All Size).'
            ],
            [
                'id' => 4, 
                'name' => 'Mousepad Gaming XL', 
                'type' => 'product', 
                'value' => 0, 
                'points' => 10000, 
                'image' => 'mousepad.png',
                'desc' => 'Mousepad speed type ukuran 80x30cm.'
            ],
        ]);
    }

    public function loadHistory()
    {
        // Mock History (In real app: Redemption::where('user_id'...))
        // We use session to persist demo history
        $this->redemptionHistory = session()->get('redemption_history_' . Auth::id(), []);
    }

    public function redeem($rewardId)
    {
        $reward = $this->rewards->firstWhere('id', $rewardId);
        
        if (!$reward) return;

        if ($this->userPoints < $reward['points']) {
            $this->dispatch('notify', message: 'Poin tidak cukup!', type: 'error');
            return;
        }

        DB::transaction(function () use ($reward) {
            // 1. Deduct Points
            $user = User::find(Auth::id());
            $user->decrement('points', $reward['points']);
            $this->userPoints = $user->points;

            // 2. Generate Reward
            $code = null;
            if ($reward['type'] == 'voucher') {
                $code = 'RDM-' . strtoupper(\Illuminate\Support\Str::random(8));
                // Create actual voucher in DB
                Voucher::create([
                    'code' => $code,
                    'type' => 'fixed',
                    'amount' => $reward['value'],
                    'quota' => 1,
                    'min_spend' => $reward['value'] * 5, // Logic example
                    'is_active' => true,
                    'start_date' => now(),
                    'end_date' => now()->addMonth()
                ]);
            }

            // 3. Log History
            $historyEntry = [
                'date' => now()->toDateTimeString(),
                'name' => $reward['name'],
                'points' => $reward['points'],
                'status' => $reward['type'] == 'voucher' ? 'Aktif' : 'Diproses',
                'code' => $code
            ];

            // Push to session mock DB
            $history = session()->get('redemption_history_' . Auth::id(), []);
            array_unshift($history, $historyEntry);
            session()->put('redemption_history_' . Auth::id(), $history);
        });

        $this->loadHistory();
        $this->dispatch('notify', message: 'Penukaran berhasil! Cek tab "Voucher Saya".', type: 'success');
    }

    public function render()
    {
        return view('livewire.member.loyalty-points');
    }
}
