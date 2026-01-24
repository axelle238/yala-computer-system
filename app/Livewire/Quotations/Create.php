<?php

namespace App\Livewire\Quotations;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Buat Penawaran Harga - Yala Computer')]
class Create extends Component
{
    public $quote_number;
    public $customer_id;
    public $valid_until;
    public $notes;
    
    public $cart = []; // ['product_id', 'name', 'price', 'qty', 'subtotal']
    public $search = '';

    public function mount()
    {
        $this->quote_number = 'QT-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        $this->valid_until = date('Y-m-d', strtotime('+7 days'));
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product) return;

        foreach ($this->cart as $key => $item) {
            if ($item['product_id'] == $productId) {
                $this->cart[$key]['qty']++;
                $this->cart[$key]['subtotal'] = $this->cart[$key]['qty'] * $this->cart[$key]['price'];
                return;
            }
        }

        $this->cart[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->sell_price,
            'qty' => 1,
            'subtotal' => $product->sell_price
        ];
    }

    public function updateQty($index, $qty)
    {
        if (isset($this->cart[$index])) {
            $this->cart[$index]['qty'] = max(1, intval($qty));
            $this->cart[$index]['subtotal'] = $this->cart[$index]['qty'] * $this->cart[$index]['price'];
        }
    }

    public function remove($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function getTotalProperty()
    {
        return array_sum(array_column($this->cart, 'subtotal'));
    }

    public function save()
    {
        $this->validate([
            'customer_id' => 'required',
            'valid_until' => 'required|date',
            'cart' => 'required|array|min:1'
        ]);

        DB::transaction(function () {
            $quote = Quotation::create([
                'quote_number' => $this->quote_number,
                'customer_id' => $this->customer_id,
                'user_id' => Auth::id(),
                'valid_until' => $this->valid_until,
                'total_amount' => $this->total,
                'status' => 'draft',
                'notes' => $this->notes
            ]);

            foreach ($this->cart as $item) {
                QuotationItem::create([
                    'quotation_id' => $quote->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price']
                ]);
            }
        });

        $this->dispatch('notify', message: 'Penawaran berhasil dibuat!', type: 'success');
        return redirect()->route('quotations.index');
    }

    public function render()
    {
        $products = [];
        if (strlen($this->search) > 2) {
            $products = Product::where('name', 'like', '%' . $this->search . '%')->take(5)->get();
        }

        return view('livewire.quotations.create', [
            'products' => $products,
            'customers' => Customer::all()
        ]);
    }
}
