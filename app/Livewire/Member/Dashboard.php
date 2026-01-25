<?php

namespace App\Livewire\Member;

use App\Models\Order;
use App\Models\SavedBuild;
use App\Models\ServiceTicket;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Member Area - Yala Computer')]
class Dashboard extends Component
{
    public function deleteBuild($id)
    {
        SavedBuild::where('id', $id)->where('user_id', Auth::id())->delete();
        $this->dispatch('notify', message: 'Rakitan dihapus.', type: 'success');
    }

    public function render()
    {
        $user = Auth::user();

        // Mock Gamification Logic (To be moved to User Model later)
        $points = $user->points ?? 0;
        $level = 'Bronze';
        $levelColor = 'bg-amber-600 border-amber-400 text-amber-100';
        $levelIcon = 'star';
        $nextLevelTarget = 10000;

        if ($points >= 50000) {
            $level = 'Diamond';
            $levelColor = 'bg-cyan-600 border-cyan-400 text-cyan-100';
            $levelIcon = 'lightning-bolt';
            $nextLevelTarget = 100000;
        } elseif ($points >= 10000) {
            $level = 'Gold';
            $levelColor = 'bg-yellow-500 border-yellow-300 text-yellow-50';
            $levelIcon = 'crown';
            $nextLevelTarget = 50000;
        }

        // Add attributes to user object on the fly for view
        $user->level = [
            'name' => $level,
            'color' => $levelColor,
            'bg' => str_replace('text', 'bg', $levelColor), // simplistic
            'icon' => $levelIcon,
        ];

        $user->next_level_progress = [
            'target' => $nextLevelTarget,
            'current' => $points,
            'remaining' => max(0, $nextLevelTarget - $points),
            'percent' => $nextLevelTarget > 0 ? min(100, ($points / $nextLevelTarget) * 100) : 100,
        ];

        // Fetch Data
        $recentOrders = Order::where('user_id', $user->id)->latest()->take(5)->get();

        $activeServices = ServiceTicket::where(function ($q) use ($user) {
            $q->where('customer_phone', $user->phone)
                ->orWhere('customer_name', $user->name);
        })
            ->whereNotIn('status', ['picked_up', 'cancelled'])
            ->latest()
            ->get();

        $savedBuilds = SavedBuild::where('user_id', $user->id)->latest()->get();

        return view('livewire.member.dashboard', [
            'user' => $user,
            'recentOrders' => $recentOrders,
            'activeServices' => $activeServices,
            'savedBuilds' => $savedBuilds,
        ]);
    }
}
