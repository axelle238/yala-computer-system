<?php

namespace App\Livewire\CRM;

use App\Models\User;
use App\Models\PointHistory;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
#[Title('Profil Pelanggan - CRM 360')]
class CustomerDetail extends Component
{
    public $customer;
    public $activeTab = 'overview'; // overview, orders, services, rma, points
    
    // Edit Profile
    public $notes;

    // Manual Point Adjustment
    public $pointAdjustment = 0;
    public $pointReason = '';

    public function mount($id)
    {
        $this->customer = User::with(['orders', 'serviceTickets', 'rmas', 'pointHistories'])->findOrFail($id);
        $this->notes = $this->customer->notes;
    }

    public function updateNotes()
    {
        $this->customer->update(['notes' => $this->notes]);
        session()->flash('success', 'Catatan CRM diperbarui.');
    }

    public function adjustPoints()
    {
        $this->validate([
            'pointAdjustment' => 'required|integer|not_in:0',
            'pointReason' => 'required|string|min:3',
        ]);

        $newBalance = $this->customer->loyalty_points + $this->pointAdjustment;
        
        if ($newBalance < 0) {
            $this->addError('pointAdjustment', 'Poin tidak boleh minus.');
            return;
        }

        // 1. Update User
        $this->customer->loyalty_points = $newBalance;
        $this->customer->save();

        // 2. Log History
        PointHistory::create([
            'user_id' => $this->customer->id,
            'amount' => $this->pointAdjustment,
            'type' => 'adjustment',
            'description' => 'Manual Adjustment: ' . $this->pointReason,
        ]);

        // 3. Check Tier Upgrade (Simple Logic)
        $this->checkTierUpgrade();

        session()->flash('success', 'Poin berhasil disesuaikan.');
        $this->reset(['pointAdjustment', 'pointReason']);
    }

    public function checkTierUpgrade()
    {
        // Simple logic based on TOTAL SPENT, not points balance usually
        // But let's check points for now or skip complex logic
        $spent = $this->customer->total_spent;
        
        $newTier = 'bronze';
        if ($spent >= 50000000) $newTier = 'platinum';
        elseif ($spent >= 20000000) $newTier = 'gold';
        elseif ($spent >= 5000000) $newTier = 'silver';

        if ($newTier !== $this->customer->loyalty_tier) {
            $this->customer->update(['loyalty_tier' => $newTier]);
            // Maybe notify user
        }
    }

    public function render()
    {
        return view('livewire.crm.customer-detail');
    }
}
