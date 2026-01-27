<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\PurchaseOrder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Manajemen Pembelian - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $cari = '';

    public function render()
    {
        // Stats Calculation
        $openPoCount = PurchaseOrder::where('status', 'ordered')->count();
        $monthlyPurchase = PurchaseOrder::where('status', '!=', 'cancelled')
            ->whereMonth('order_date', now()->month)
            ->sum('total_amount');
        $supplierCount = \App\Models\Supplier::count();

        $orders = PurchaseOrder::with('pemasok')
            ->where('po_number', 'like', '%'.$this->cari.'%')
            ->orWhereHas('pemasok', function ($q) {
                $q->where('name', 'like', '%'.$this->cari.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.purchase-orders.index', [
            'orders' => $orders,
            'openPoCount' => $openPoCount,
            'monthlyPurchase' => $monthlyPurchase,
            'supplierCount' => $supplierCount,
        ]);
    }
}
