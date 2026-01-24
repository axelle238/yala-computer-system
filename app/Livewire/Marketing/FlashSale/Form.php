<?php

namespace App\Livewire\Marketing\FlashSale;

use App\Models\FlashSale;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Tambah Flash Sale - Yala Computer')]
class Form extends Component
{
    public $product_id;
    public $discount_price;
    public $start_time;
    public $end_time;
    public $quota = 10;

    // Search
    public $searchProduct = '';
    public $searchResults = [];
    public $selectedProductName = '';
    public $originalPrice = 0;

    public function updatedSearchProduct()
    {
        if (strlen($this->searchProduct) > 2) {
            $this->searchResults = Product::where('name', 'like', '%' . $this->searchProduct . '%')
                ->where('is_active', true)
                ->take(5)->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectProduct($id)
    {
        $product = Product::find($id);
        $this->product_id = $product->id;
        $this->selectedProductName = $product->name;
        $this->originalPrice = $product->sell_price;
        $this->searchProduct = ''; // Close dropdown
        $this->searchResults = [];
    }

    public function save()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'discount_price' => 'required|numeric|min:1|lt:originalPrice',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'quota' => 'required|integer|min:1',
        ]);

        FlashSale::create([
            'product_id' => $this->product_id,
            'discount_price' => $this->discount_price,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'quota' => $this->quota,
            'is_active' => true,
        ]);

        $this->dispatch('notify', message: 'Flash sale berhasil dibuat!', type: 'success');
        return redirect()->route('marketing.flash-sale.index');
    }

    public function render()
    {
        return view('livewire.marketing.flash-sale.form');
    }
}
