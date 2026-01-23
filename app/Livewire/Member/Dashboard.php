<?php

namespace App\Livewire\Member;

use App\Models\Order;
use App\Models\ServiceTicket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')] // Pakai layout toko tapi ada menu member
#[Title('Member Area - Yala Computer')]
class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        $recentOrders = Order::where('user_id', $user->id)->latest()->take(3)->get();
        $activeServices = ServiceTicket::where('customer_phone', $user->phone) // Match by phone or email ideally
            ->whereNotIn('status', ['picked_up', 'cancelled'])
            ->latest()
            ->get();

        return view('livewire.member.dashboard', [
            'user' => $user,
            'recentOrders' => $recentOrders,
            'activeServices' => $activeServices
        ]);
    }
}
