<?php

namespace App\Livewire\Store;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Katalog Produk Lengkap - Yala Computer')]
class Catalog extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $sort = 'latest'; // latest, price_asc, price_desc
    public $minPrice = 0;
    public $maxPrice = 50000000;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategory() { $this->resetPage(); }
    public function updatedSort() { $this->resetPage(); }

    public function addToCart($id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
        $this->dispatch('notify', message: 'Produk ditambahkan ke keranjang!', type: 'success');
    }

    public function addToCompare($id)
    {
        $compare = session()->get('compare_products', []);
        
        if (in_array($id, $compare)) {
            $this->dispatch('notify', message: 'Produk sudah ada di perbandingan.', type: 'info');
            return;
        }

        if (count($compare) >= 4) {
            $this->dispatch('notify', message: 'Maksimal 4 produk untuk dibandingkan.', type: 'error');
            return;
        }

        $compare[] = $id;
        session()->put('compare_products', $compare);
        $this->dispatch('notify', message: 'Ditambahkan ke perbandingan!', type: 'success');
    }

    public function render()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('sku', 'like', '%'.$this->search.'%');
            })
            ->when($this->category, function($q) {
                $q->whereHas('category', fn($c) => $c->where('slug', $this->category));
            })
            ->whereBetween('sell_price', [$this->minPrice, $this->maxPrice])
            ->when($this->sort === 'latest', fn($q) => $q->latest())
            ->when($this->sort === 'price_asc', fn($q) => $q->orderBy('sell_price', 'asc'))
            ->when($this->sort === 'price_desc', fn($q) => $q->orderBy('sell_price', 'desc'))
            ->paginate(12);

        $categories = Category::where('is_active', true)->get();

        return view('livewire.store.catalog', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}