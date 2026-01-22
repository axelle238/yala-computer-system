<?php

namespace App\Livewire\Marketing\FlashSale;

use App\Models\FlashSale;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Buat Flash Sale - Yala Computer')]
class Form extends Component
{
    public $product_id;
    public $discount_price;
    public $start_time;
    public $end_time;
    public $quota;
    public $is_active = true;

    // Product Search
    public $search = '';
    public $selectedProduct = null;

    public function updatedSearch()
    {
        $this->selectedProduct = null;
    }

    public function selectProduct($id)
    {
        $this->selectedProduct = Product::find($id);
        $this->product_id = $id;
        $this->search = $this->selectedProduct->name;
    }

    public function save()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'discount_price' => 'required|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'quota' => 'required|integer|min:1',
        ]);

        if ($this->selectedProduct && $this->discount_price >= $this->selectedProduct->sell_price) {
            $this->addError('discount_price', 'Harga diskon harus lebih rendah dari harga jual normal.');
            return;
        }

        FlashSale::create([
            'product_id' => $this->product_id,
            'discount_price' => $this->discount_price,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'quota' => $this->quota,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Flash Sale berhasil dijadwalkan.');
        return redirect()->route('marketing.flash-sale.index');
    }

    public function render()
    {
        $products = [];
        if (strlen($this->search) > 2) {
            $products = Product::where('name', 'like', '%' . $this->search . '%')
                ->where('is_active', true)
                ->take(5)->get();
        }

        return view('livewire.marketing.flash-sale.form', [
            'products' => $products
        ]);
    }
}