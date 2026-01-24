<?php

namespace App\Livewire\Member;

use App\Models\PointHistory;
use App\Models\Voucher;
use App\Models\VoucherUsage; // We use this to track 'ownership' if we want, or just create code. 
// Actually standard voucher logic is: use code at checkout.
// Redeeming here means: "Unlock" a code or "Get" a code.
// Simplest way: User pays points -> Gets a unique Voucher Code (or existing one is assigned to them).
// Let's assume we give them a unique single-use code based on the master voucher template.

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Loyalty Points & Rewards - Yala Computer')]
class LoyaltyPoints extends Component
{
    use WithPagination;

    public function redeem($voucherId)
    {
        $user = Auth::user();
        $template = Voucher::find($voucherId);

        if (!$template || !$template->is_active) {
            $this->dispatch('notify', message: 'Voucher tidak tersedia.', type: 'error');
            return;
        }

        if ($user->points < $template->points_cost) {
            $this->dispatch('notify', message: 'Poin tidak mencukupi!', type: 'error');
            return;
        }

        DB::transaction(function () use ($user, $template) {
            // 1. Deduct Points
            $user->decrement('points', $template->points_cost);

            // 2. Log History
            PointHistory::create([
                'user_id' => $user->id,
                'type' => 'redeemed',
                'amount' => -$template->points_cost,
                'description' => 'Redeemed: ' . $template->name,
                'reference_type' => Voucher::class,
                'reference_id' => $template->id
            ]);

            // 3. Generate Personal Code
            // We clone the template into a new Voucher but strictly for this user with usage_limit=1
            // OR we just assume they use the master code? No, usually master code is public.
            // If it's a reward, it should be exclusive.
            
            // Strategy: Create a new Voucher entry specific to this user based on template
            $newCode = $template->code . '-' . strtoupper(Str::random(5));
            
            $personalVoucher = Voucher::create([
                'code' => $newCode,
                'name' => $template->name . ' (Redeemed)',
                'description' => $template->description,
                'type' => $template->type,
                'amount' => $template->amount,
                'min_spend' => $template->min_spend,
                'max_discount' => $template->max_discount,
                'usage_limit' => 1,
                'usage_per_user' => 1,
                'start_date' => now(),
                'end_date' => now()->addMonth(), // Valid for 30 days
                'is_active' => true,
                'points_cost' => 0, // No cost to use, cost paid
                'is_public' => false
            ]);
            
            // We can also track "UserVoucher" ownership if needed, but the code itself is enough.
        });

        $this->dispatch('notify', message: 'Voucher berhasil diklaim! Cek email/notifikasi Anda.', type: 'success');
    }

    public function render()
    {
        $user = Auth::user();
        
        $history = PointHistory::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $rewards = Voucher::redeemable()
            ->orderBy('points_cost')
            ->get();

        return view('livewire.member.loyalty-points', [
            'user' => $user,
            'history' => $history,
            'rewards' => $rewards,
            'nextLevel' => $user->next_level_progress
        ]);
    }
}
