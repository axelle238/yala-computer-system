<?php

namespace App\Livewire\Procurement\GoodsReceive;

use App\Models\PurchaseOrder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Penerimaan Barang (GRN)')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        // Hanya tampilkan PO yang statusnya 'ordered' (belum diterima penuh)
        // Atau 'partial' jika ada fitur partial receive
        $orders = PurchaseOrder::with('supplier')
            ->whereIn('status', ['ordered', 'partial'])
            ->where('po_number', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.procurement.goods-receive.index', ['orders' => $orders]);
    }
}
