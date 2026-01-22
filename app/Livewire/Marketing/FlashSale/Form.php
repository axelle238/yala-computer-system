<?php

namespace App\Livewire\Marketing\FlashSale;

use App\Models\FlashSale;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Form Flash Sale - Yala Computer')]
class Form extends Component
{
    public $product_id;
    public $discount_price;
    public $start_time;
    public $end_time;
    public $quota = 10;

    public $products = [];

    public function mount()
    {
        $this->products = Product::where('is_active', true)->get();
        $this->start_time = now()->format('Y-m-d\TH:i');
        $this->end_time = now()->addHours(24)->format('Y-m-d\TH:i');
    }

    public function save()
    {
        $this->validate([
            'product_id' => 'required',
            'discount_price' => 'required|numeric|min:0',
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
        ]);

        session()->flash('success', 'Flash Sale berhasil dijadwalkan.');
        return redirect()->route('marketing.flash-sale.index');
    }

    public function render()
    {
        return view('livewire.marketing.flash-sale.form');
    }
}
