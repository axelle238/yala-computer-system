<?php

namespace App\Livewire\Logistics;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen Pengiriman (Logistics)')]
class Manager extends Component
{
    use WithPagination;

    public $filterStatus = 'processing'; // pending, processing, shipped, delivered
    public $search = '';
    
    // Tracking Update
    public $updateTrackingModal = false;
    public $selectedOrderId;
    public $trackingNumber;

    public function updatedFilterStatus() { $this->resetPage(); }

    public function markAsProcessing($id)
    {
        Order::find($id)->update(['status' => 'processing']);
        $this->dispatch('notify', message: 'Status diubah: Diproses', type: 'success');
    }

    public function openTrackingModal($id)
    {
        $this->selectedOrderId = $id;
        $this->trackingNumber = '';
        $this->updateTrackingModal = true;
    }

    public function saveTracking()
    {
        $this->validate(['trackingNumber' => 'required|string|min:5']);
        
        Order::find($this->selectedOrderId)->update([
            'status' => 'shipped',
            'tracking_number' => $this->trackingNumber
        ]);

        $this->updateTrackingModal = false;
        $this->dispatch('notify', message: 'Resi diinput. Status: Dikirim', type: 'success');
    }

    public function render()
    {
        $orders = Order::where('status', $this->filterStatus)
            ->where(function($q) {
                $q->where('order_number', 'like', '%'.$this->search.'%')
                  ->orWhere('guest_name', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.logistics.manager', ['orders' => $orders]);
    }
}
