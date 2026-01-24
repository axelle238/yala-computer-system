<?php

namespace App\Livewire\Store;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class GlobalSearch extends Component
{
    public $query = '';
    public $results = [];
    public $recentSearches = [];
    public $isFocused = false;

    public function mount()
    {
        $this->recentSearches = Session::get('recent_searches', []);
    }

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            return;
        }

        // 1. Search Products
        $products = Product::where('name', 'like', '%' . $this->query . '%')
            ->where('is_active', true)
            ->take(5)
            ->get();

        // 2. Search Categories
        $categories = Category::where('name', 'like', '%' . $this->query . '%')
            ->take(3)
            ->get();

        $this->results = [
            'products' => $products,
            'categories' => $categories
        ];
    }

    public function selectResult($name, $type, $slug = null)
    {
        // Add to recent
        if (!in_array($name, $this->recentSearches)) {
            array_unshift($this->recentSearches, $name);
            $this->recentSearches = array_slice($this->recentSearches, 0, 5); // Keep last 5
            Session::put('recent_searches', $this->recentSearches);
        }

        if ($type === 'product') {
            return redirect()->route('product.detail', $slug); // ID passed as slug here
        } elseif ($type === 'category') {
            return redirect()->route('store.catalog', ['category' => $slug]);
        } else {
            return redirect()->route('store.catalog', ['search' => $name]);
        }
    }

    public function clearRecent()
    {
        Session::forget('recent_searches');
        $this->recentSearches = [];
    }

    public function render()
    {
        return view('livewire.store.global-search');
    }
}
