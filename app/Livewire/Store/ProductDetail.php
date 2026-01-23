<?php

namespace App\Livewire\Store;

use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
class ProductDetail extends Component
{
    public $product;
    public $cart = [];
    public $compareList = [];

    public function mount($id)
    {
        $this->product = Product::with('category', 'reviews')->findOrFail($id);
        $this->cart = Session::get('cart', []);
        $this->compareList = Session::get('compareList', []); // Assuming compare list is also in session or local storage handling
    }

    public function render()
    {
        // Get related products
        $relatedProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->take(4)
            ->get();

        return view('livewire.store.product-detail', [
            'relatedProducts' => $relatedProducts
        ])->title($this->product->name . ' - Yala Computer');
    }
}
