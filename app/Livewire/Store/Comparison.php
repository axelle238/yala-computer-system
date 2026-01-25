<?php

namespace App\Livewire\Store;

use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Perbandingan Produk - Yala Computer')]
class Comparison extends Component
{
    public $products = [];

    public function mount()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $ids = Session::get('comparison_list', []);
        
        if (!empty($ids)) {
            $this->products = Product::whereIn('id', $ids)->get();
        } else {
            $this->products = collect();
        }
    }

    public function removeFromCompare($id)
    {
        $ids = Session::get('comparison_list', []);
        
        if (($key = array_search($id, $ids)) !== false) {
            unset($ids[$key]);
            Session::put('comparison_list', array_values($ids)); // Reindex
            
            $this->loadProducts();
            $this->dispatch('notify', message: 'Produk dihapus dari perbandingan.');
        }
    }

    public function addToCart($id)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        
        Session::put('cart', $cart);
        $this->dispatch('cart-updated');
        $this->dispatch('notify', message: 'Produk masuk keranjang!', type: 'success');
    }

    public function render()
    {
        return view('livewire.store.comparison');
    }
}
