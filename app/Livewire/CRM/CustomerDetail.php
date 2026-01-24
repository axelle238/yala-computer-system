<?php

namespace App\Livewire\CRM;

use App\Models\CustomerInteraction;
use App\Models\LoyaltyLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Customer 360 View - Yala Computer')]
class CustomerDetail extends Component
{
    use WithPagination;

    public $customerId;
    public $activeTab = 'overview'; // overview, orders, interactions, loyalty
    
    // Interaction Form
    public $interactionType = 'note';
    public $interactionContent;
    public $interactionDate;

    // Point Adjustment
    public $pointAction = 'add';
    public $pointAmount;
    public $pointReason;

    public function mount($id)
    {
        $this->customerId = $id;
        $this->interactionDate = date('Y-m-d');
    }

    public function getCustomerProperty()
    {
        return User::with(['customerGroup', 'orders'])->findOrFail($this->customerId);
    }

    public function addInteraction()
    {
        $this->validate([
            'interactionContent' => 'required|string',
            'interactionDate' => 'required|date',
        ]);

        CustomerInteraction::create([
            'user_id' => $this->customerId,
            'staff_id' => Auth::id(),
            'type' => $this->interactionType,
            'content' => $this->interactionContent,
            'interaction_date' => $this->interactionDate,
            'outcome' => 'logged'
        ]);

        $this->reset(['interactionContent']);
        $this->dispatch('notify', message: 'Interaksi dicatat.', type: 'success');
    }

    public function adjustPoints()
    {
        $this->validate([
            'pointAmount' => 'required|integer|min:1',
            'pointReason' => 'required|string',
        ]);

        $customer = $this->customer;
        $points = (int) $this->pointAmount;

        if ($this->pointAction === 'add') {
            $customer->increment('points', $points);
        } else {
            if ($customer->points < $points) {
                $this->dispatch('notify', message: 'Poin tidak cukup.', type: 'error');
                return;
            }
            $customer->decrement('points', $points);
        }

        LoyaltyLog::create([
            'user_id' => $this->customerId,
            'type' => $this->pointAction === 'add' ? 'adjustment_add' : 'adjustment_sub',
            'points' => $this->pointAction === 'add' ? $points : -$points,
            'reference_type' => 'manual',
            'description' => $this->pointReason . ' (by ' . Auth::user()->name . ')'
        ]);

        $this->reset(['pointAmount', 'pointReason']);
        $this->dispatch('notify', message: 'Saldo poin diperbarui.', type: 'success');
    }

    public function recalculateTier()
    {
        $this->customer->recalculateLevel();
        $this->dispatch('notify', message: 'Level pelanggan dihitung ulang.', type: 'success');
    }

    public function render()
    {
        $interactions = CustomerInteraction::where('user_id', $this->customerId)->latest()->paginate(5, ['*'], 'interactionsPage');
        $orders = $this->customer->orders()->latest()->paginate(5, ['*'], 'ordersPage');
        $logs = LoyaltyLog::where('user_id', $this->customerId)->latest()->paginate(10, ['*'], 'logsPage');

        return view('livewire.c-r-m.customer-detail', [
            'interactions' => $interactions,
            'orders' => $orders,
            'logs' => $logs
        ]);
    }
}
