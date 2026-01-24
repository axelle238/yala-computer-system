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
    
    // Add Listeners
    protected $listeners = ['addToWishlist'];

    public function mount($id)
    {
        $this->product = Product::with(['category', 'reviews', 'flashSales'])->findOrFail($id);
    }

    public function addToCart()
    {
        $this->dispatch('addToCart', productId: $this->product->id);
    }

    public function addToWishlist($productId)
    {
        if (!auth()->check()) {
            $this->dispatch('notify', message: 'Silakan login terlebih dahulu.', type: 'error');
            return;
        }

        $exists = \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $productId)->first();

        if ($exists) {
            $exists->delete();
            $this->dispatch('notify', message: 'Dihapus dari Wishlist.');
        } else {
            \App\Models\Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $productId
            ]);
            $this->dispatch('notify', message: 'Ditambahkan ke Wishlist!', type: 'success');
        }
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
