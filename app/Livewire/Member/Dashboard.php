<?php

namespace App\Livewire\Member;

use App\Models\Order;
use App\Models\SavedBuild;
use App\Models\ServiceTicket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

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
        
        $recentOrders = Order::where('user_id', $user->id)->latest()->take(5)->get();
        
        // Match service by email or phone (Asumsi user profile punya phone yg sama dgn ticket)
        $activeServices = ServiceTicket::where(function($q) use ($user) {
                $q->where('customer_phone', $user->phone)
                  ->orWhere('customer_email', $user->email); // Tambah kolom email di ticket jika belum ada, atau pakai phone saja
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