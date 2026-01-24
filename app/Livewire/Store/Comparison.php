<?php

namespace App\Livewire\Store;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Bandingkan Produk - Yala Computer')]
class Comparison extends Component
{
    public $productIds = [];
    public $products = [];

    public function mount()
    {
        // Load from session
        $this->productIds = session()->get('compare_products', []);
        $this->loadProducts();
    }

    public function loadProducts()
    {
        if (!empty($this->productIds)) {
            $this->products = Product::whereIn('id', $this->productIds)->get();
        } else {
            $this->products = [];
        }
    }

    public function removeFromCompare($id)
    {
        $this->productIds = array_diff($this->productIds, [$id]);
        session()->put('compare_products', $this->productIds);
        $this->loadProducts();
    }

    public function clearAll()
    {
        session()->forget('compare_products');
        $this->productIds = [];
        $this->products = [];
    }

    public function render()
    {
        // Gather unique spec keys for table rows
        $specKeys = [];
        foreach ($this->products as $product) {
            if ($product->specifications && is_array($product->specifications)) {
                $specKeys = array_unique(array_merge($specKeys, array_keys($product->specifications)));
            }
        }
        sort($specKeys);

        return view('livewire.store.comparison', [
            'specKeys' => $specKeys
        ]);
    }
}