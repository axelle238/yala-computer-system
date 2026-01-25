<?php

namespace App\Livewire\Rma;

use App\Models\Order;
use App\Models\Product;
use App\Models\Rma;
use App\Models\RmaItem;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Form RMA - Yala Computer')]
class Form extends Component
{
    public ?Rma $rma = null;

    public $rma_number;

    public $guest_name;

    public $guest_phone;

    public $status = 'pending';

    public $admin_notes;

    // Items
    public $items = []; // [['product_id', 'serial', 'problem']]

    // Search Order
    public $searchOrder = '';

    public $selectedOrder = null;

    public function mount($id = null)
    {
        if ($id) {
            $this->rma = Rma::with('items')->findOrFail($id);
            $this->rma_number = $this->rma->rma_number;
            $this->guest_name = $this->rma->guest_name;
            $this->status = $this->rma->status;
            $this->admin_notes = $this->rma->admin_notes;

            foreach ($this->rma->items as $item) {
                $this->items[] = [
                    'product_id' => $item->product_id,
                    'serial' => $item->serial_number,
                    'problem' => $item->problem_description,
                ];
            }
        } else {
            $this->rma_number = 'RMA-'.date('Ym').'-'.strtoupper(Str::random(4));
            $this->items[] = ['product_id' => '', 'serial' => '', 'problem' => ''];
        }
    }

    public function loadOrder()
    {
        $order = Order::with('items.product')->where('order_number', $this->searchOrder)->first();
        if ($order) {
            $this->selectedOrder = $order;
            $this->guest_name = $order->guest_name;
            $this->guest_phone = $order->guest_whatsapp;
            // Reset items and pre-fill from order (optional, usually user selects specific item)
        }
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'serial' => '', 'problem' => ''];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate([
            'guest_name' => 'required',
            'items.*.product_id' => 'required',
        ]);

        $data = [
            'rma_number' => $this->rma_number,
            'guest_name' => $this->guest_name,
            'status' => $this->status,
            'admin_notes' => $this->admin_notes,
            'order_id' => $this->selectedOrder?->id ?? $this->rma?->order_id,
        ];

        if ($this->rma) {
            $this->rma->update($data);
            $this->rma->items()->delete();
        } else {
            $this->rma = Rma::create($data);
        }

        foreach ($this->items as $item) {
            RmaItem::create([
                'rma_id' => $this->rma->id,
                'product_id' => $item['product_id'],
                'serial_number' => $item['serial'],
                'problem_description' => $item['problem'],
            ]);
        }

        return redirect()->route('rma.index');
    }

    public function render()
    {
        return view('livewire.rma.form', [
            'products' => Product::all(),
        ]);
    }
}
