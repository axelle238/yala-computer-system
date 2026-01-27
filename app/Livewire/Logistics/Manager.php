<?php

namespace App\Livewire\Logistics;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen Pengiriman (Logistics)')]
class Manager extends Component
{
    use WithPagination;

    public $filterStatus = 'processing'; // pending, processing, shipped, delivered

    public $cari = '';

    // View State
    public $activeAction = null; // null, 'tracking'

    public $selectedOrderId;

    public $trackingNumber;

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function markAsProcessing($id)
    {
        Order::find($id)->update(['status' => 'processing']);
        $this->dispatch('notify', message: 'Status diubah: Diproses', type: 'success');
    }

    public function openTrackingPanel($id)
    {
        $this->selectedOrderId = $id;
        $this->trackingNumber = '';
        $this->activeAction = 'tracking';
        $this->resetValidation();
    }

    public function closeTrackingPanel()
    {
        $this->activeAction = null;
        $this->reset(['selectedOrderId', 'trackingNumber']);
    }

    public function saveTracking()
    {
        $this->validate(['trackingNumber' => 'required|string|min:5']);

        Order::find($this->selectedOrderId)->update([
            'status' => 'shipped',
            'tracking_number' => $this->trackingNumber,
        ]);

        $this->closeTrackingPanel();
        $this->dispatch('notify', message: 'Resi diinput. Status: Dikirim', type: 'success');
    }

    public function render()
    {
        $orders = Order::where('status', $this->filterStatus)
            ->where(function ($q) {
                $q->where('order_number', 'like', '%'.$this->cari.'%')
                    ->orWhere('guest_name', 'like', '%'.$this->cari.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.logistics.manager', ['orders' => $orders]);
    }
}
