<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Pembelian - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $orders = PurchaseOrder::with('supplier')
            ->where('po_number', 'like', '%' . $this->search . '%')
            ->orWhereHas('supplier', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.purchase-orders.index', ['orders' => $orders]);
    }
}
