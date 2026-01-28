<?php

namespace App\Livewire\Quotations;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Buat Penawaran - Yala Computer')]
class Create extends Component
{
    // Header Data
    public $customer_id;

    public $valid_until;

    public $notes;

    public $terms;

    // Items Data
    public $items = []; // [product_id, qty, price, subtotal]

    // Search Logic
    public $searchProduct = '';

    public $searchResults = [];

    public function mount()
    {
        $this->valid_until = now()->addDays(14)->format('Y-m-d');
        $this->terms = "1. Harga dapat berubah sewaktu-waktu.\n2. Pembayaran DP 50% untuk PO diatas 10jt.\n3. Barang yang sudah dibeli tidak dapat ditukar kecuali cacat pabrik.";
        $this->addItem(); // Start with 1 empty row
    }

    public function addItem()
    {
        $this->items[] = [
            'product_id' => '',
            'product_name' => '',
            'qty' => 1,
            'price' => 0,
            'subtotal' => 0,
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updateProductSearch($query)
    {
        $this->searchProduct = $query;
        if (strlen($query) > 2) {
            $this->searchResults = Product::where('name', 'like', "%{$query}%")
                ->orWhere('sku', 'like', "%{$query}%")
                ->take(5)->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectProduct($index, $productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $this->items[$index]['product_id'] = $product->id;
            $this->items[$index]['product_name'] = $product->name;
            $this->items[$index]['price'] = $product->sell_price;
            $this->calculateRow($index);
        }
        $this->searchResults = [];
        $this->searchProduct = '';
    }

    public function calculateRow($index)
    {
        $qty = (int) $this->items[$index]['qty'];
        $price = (float) $this->items[$index]['price'];
        $this->items[$index]['subtotal'] = $qty * $price;
    }

    public function save()
    {
        $this->validate([
            'customer_id' => 'required',
            'valid_until' => 'required|date',
            'items.*.product_id' => 'required',
            'items.*.qty' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () {
            $total = array_sum(array_column($this->items, 'subtotal'));

            $quotation = Quotation::create([
                'quotation_number' => 'Q-'.date('Ymd').'-'.rand(100, 999),
                'customer_id' => $this->customer_id,
                'user_id' => Auth::id(),
                'valid_until' => $this->valid_until,
                'total_amount' => $total,
                'status' => 'sent', // Assume sent immediately
                'approval_status' => 'pending',
                'notes' => $this->notes,
                'terms_and_conditions' => $this->terms,
            ]);

            foreach ($this->items as $item) {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }
        });

        $this->dispatch('notify', message: 'Penawaran berhasil dibuat!', type: 'success');

        return redirect()->route('admin.penawaran.indeks');
    }

    public function render()
    {
        return view('livewire.quotations.create', [
            'customers' => Customer::all(),
        ]);
    }
}
