<?php

namespace App\Livewire\Quotations;

use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Form Penawaran - Yala Computer')]
class Form extends Component
{
    public ?Quotation $quotation = null;

    // Form Fields
    public $quotation_number;

    public $user_id;

    public $valid_until;

    public $notes;

    public $terms_and_conditions;

    public $approval_status = 'pending';

    // Items
    public $items = []; // [ ['product_id', 'item_name', 'quantity', 'unit_price', 'total_price'] ]

    // Search
    public $searchProduct = '';

    public $searchResults = [];

    public function mount($id = null)
    {
        if ($id) {
            $this->quotation = Quotation::with('item')->findOrFail($id);
            $this->quotation_number = $this->quotation->quotation_number;
            $this->user_id = $this->quotation->user_id;
            $this->valid_until = $this->quotation->valid_until?->format('Y-m-d');
            $this->notes = $this->quotation->notes;
            $this->terms_and_conditions = $this->quotation->terms_and_conditions;
            $this->approval_status = $this->quotation->approval_status;

            foreach ($this->quotation->item as $item) {
                $this->items[] = [
                    'product_id' => $item->product_id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                ];
            }
        } else {
            $this->quotation_number = 'Q-'.date('Ymd').'-'.strtoupper(Str::random(4));
            $this->valid_until = now()->addDays(7)->format('Y-m-d');
        }
    }

    public function updatedSearchProduct()
    {
        if (strlen($this->searchProduct) > 2) {
            $this->searchResults = Product::where('name', 'like', '%'.$this->searchProduct.'%')
                ->limit(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function addProduct($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $this->items[] = [
                'product_id' => $product->id,
                'item_name' => $product->name,
                'quantity' => 1,
                'unit_price' => $product->sell_price,
                'total_price' => $product->sell_price,
            ];
        }
        $this->searchProduct = '';
        $this->searchResults = [];
        $this->calculateTotals();
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    public function updateItem($index)
    {
        $item = &$this->items[$index];
        $item['total_price'] = $item['quantity'] * $item['unit_price'];
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        // Wrapper for UI reactivity
    }

    public function getSubtotalProperty()
    {
        return collect($this->items)->sum('total_price');
    }

    public function save()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'valid_until' => 'required|date',
            'items' => 'required|array|min:1',
        ], [
            'user_id.required' => 'Pelanggan wajib dipilih.',
            'user_id.exists' => 'Pelanggan tidak valid.',
            'valid_until.required' => 'Tanggal berlaku wajib diisi.',
            'valid_until.date' => 'Format tanggal tidak valid.',
            'items.required' => 'Item penawaran wajib diisi.',
            'items.min' => 'Minimal harus ada satu item.',
        ]);

        DB::transaction(function () {
            $data = [
                'user_id' => $this->user_id,
                'valid_until' => $this->valid_until,
                'notes' => $this->notes,
                'terms_and_conditions' => $this->terms_and_conditions,
                'approval_status' => $this->approval_status,
                'total_amount' => $this->subtotal,
            ];

            if ($this->quotation) {
                $this->quotation->update($data);
                $this->quotation->item()->delete();
            } else {
                $data['quotation_number'] = $this->quotation_number;
                $data['status'] = 'draft';
                $this->quotation = Quotation::create($data);
            }

            foreach ($this->items as $item) {
                QuotationItem::create([
                    'quotation_id' => $this->quotation->id,
                    'product_id' => $item['product_id'],
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);
            }
        });

        $this->dispatch('notify', message: 'Penawaran berhasil disimpan.', type: 'success');

        return redirect()->route('quotations.index');
    }

    public function render()
    {
        return view('livewire.quotations.form', [
            'users' => User::limit(20)->get(), // Simplified user select
        ]);
    }
}
