<?php

namespace App\Livewire\PurchaseRequisitions;

use App\Models\Product;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionItem;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Buat Pengajuan (PR) - Yala Computer')]
class Create extends Component
{
    public $pr_number;

    public $required_date;

    public $notes;

    // Items
    public $items = []; // [['product_id' => '', 'qty' => 1, 'notes' => '']]

    public function mount()
    {
        $this->pr_number = 'PR-'.date('Ymd').'-'.strtoupper(Str::random(4));
        $this->required_date = date('Y-m-d', strtotime('+3 days')); // Default +3 days
        $this->items[] = ['product_id' => '', 'qty' => 1, 'notes' => ''];
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'qty' => 1, 'notes' => ''];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate([
            'required_date' => 'required|date',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $pr = PurchaseRequisition::create([
            'pr_number' => $this->pr_number,
            'requested_by' => auth()->id(),
            'required_date' => $this->required_date,
            'status' => 'pending',
            'notes' => $this->notes,
        ]);

        foreach ($this->items as $item) {
            PurchaseRequisitionItem::create([
                'purchase_requisition_id' => $pr->id,
                'product_id' => $item['product_id'],
                'quantity_requested' => $item['qty'],
                'notes' => $item['notes'],
            ]);
        }

        session()->flash('success', 'Pengajuan (PR) berhasil dibuat.');

        return redirect()->route('purchase-requisitions.index');
    }

    public function render()
    {
        return view('livewire.purchase-requisitions.create', [
            'products' => Product::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
