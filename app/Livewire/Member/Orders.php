<?php

namespace App\Livewire\Member;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Riwayat Pesanan - Yala Computer')]
class Orders extends Component
{
    public function render()
    {
        return view('livewire.member.orders', [
            'orders' => Order::where('user_id', Auth::id())->latest()->get(),
        ]);
    }
}
