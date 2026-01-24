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
    public $specKeys = [];

    public function mount()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $ids = Session::get('compareList', []);
        
        if (empty($ids)) {
            $this->products = collect();
            $this->specKeys = [];
            return;
        }

        $this->products = Product::whereIn('id', $ids)->get();
        
        // Extract all unique spec keys
        $keys = collect();
        foreach ($this->products as $product) {
            if (is_array($product->specifications)) {
                $keys = $keys->merge(array_keys($product->specifications));
            }
        }
        
        // Filter and sort keys if needed (e.g. remove internal keys)
        $this->specKeys = $keys->unique()->values()->all();
    }

    public function removeProduct($id)
    {
        $ids = Session::get('compareList', []);
        
        // Remove ID
        $ids = array_diff($ids, [$id]);
        
        Session::put('compareList', $ids);
        
        // Reload
        $this->loadProducts();
        $this->dispatch('notify', message: 'Produk dihapus dari perbandingan.', type: 'success');
    }

    public function clearComparison()
    {
        Session::forget('compareList');
        $this->products = collect();
        $this->dispatch('notify', message: 'Daftar perbandingan dikosongkan.');
    }

    public function render()
    {
        return view('livewire.store.comparison');
    }
}
