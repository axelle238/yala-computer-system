<?php

namespace App\Livewire\Store;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Layout('layouts.store')]
#[Title('Katalog Produk Lengkap - Yala Computer')]
class Catalog extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $category = ''; // Slug

    #[Url]
    public $sort = 'newest'; // newest, price_low, price_high, name

    #[Url]
    public $min_price = 0;

    #[Url]
    public $max_price = 50000000;

    // Dynamic Specs Filters (e.g. ['ram' => '16GB', 'storage' => '1TB'])
    public $specs = []; 

    public function mount()
    {
        // Initialize filters if needed
    }

    public function updated($property)
    {
        if ($property !== 'page') {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'category', 'sort', 'min_price', 'max_price', 'specs']);
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::withCount('products')->get();

        $query = Product::query()->where('is_active', true);

        // 1. Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Category
        if ($this->category) {
            $query->whereHas('category', function($q) {
                $q->where('slug', $this->category);
            });
        }

        // 3. Price Range
        if ($this->min_price > 0) {
            $query->where('sell_price', '>=', $this->min_price);
        }
        if ($this->max_price < 50000000) {
            $query->where('sell_price', '<=', $this->max_price);
        }

        // 4. Dynamic Specs Filter (Advanced)
        // Example: specs['ram'] = '8GB' -> specifications->ram == '8GB'
        // Note: JSON querying depends on DB (MySQL 5.7+ supports -> operator)
        foreach ($this->specs as $key => $value) {
            if (!empty($value)) {
                // Flexible match using JSON contains or simple string match in JSON
                // SQLite/MySQL compatible approach for simple key-value in JSON:
                $query->whereJsonContains("specifications->{$key}", $value);
                // Or if it's not an array in JSON but a string:
                // $query->where("specifications->{$key}", $value);
                // Let's stick to whereRaw for maximum compatibility if needed, but whereJsonContains is standard Laravel.
                // However, often specs are just strings. `where('specifications->ram', 'like', "%$value%")` might be safer for partial matches.
                // Let's use standard where for JSON key
                 $query->where("specifications->{$key}", $value);
            }
        }

        // 5. Sorting
        switch ($this->sort) {
            case 'price_low':
                $query->orderBy('sell_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('sell_price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);

        // Dynamic Filter Generation based on current category
        // Only show relevant spec filters if a category is selected
        $availableFilters = [];
        if ($this->category) {
            $catId = $categories->where('slug', $this->category)->first()?->id;
            if ($catId) {
                // Get all products in this category to parse their specs keys
                // This is heavy, cache it in production. For now, simple logic.
                // Or hardcode common keys per category slug.
                // Let's try hardcoding for robustness first.
                $availableFilters = $this->getFiltersForCategory($this->category);
            }
        }

        return view('livewire.store.catalog', [
            'products' => $products,
            'categories' => $categories,
            'availableFilters' => $availableFilters,
        ]);
    }

    private function getFiltersForCategory($slug)
    {
        // Define common spec keys for known categories
        $map = [
            'laptop' => [
                'processor' => ['Intel Core i3', 'Intel Core i5', 'Intel Core i7', 'AMD Ryzen 5', 'AMD Ryzen 7'],
                'ram' => ['4GB', '8GB', '16GB', '32GB'],
                'storage' => ['256GB SSD', '512GB SSD', '1TB SSD'],
            ],
            'monitor' => [
                'resolution' => ['FHD', '2K', '4K'],
                'refresh_rate' => ['60Hz', '144Hz', '165Hz', '240Hz'],
                'panel' => ['IPS', 'VA', 'TN'],
            ],
            'processor' => [
                'socket' => ['LGA1700', 'AM4', 'AM5'],
                'cores' => ['4', '6', '8', '12', '16'],
            ],
            // Add more as needed
        ];

        // Fuzzy match or direct match
        foreach ($map as $key => $filters) {
            if (str_contains($slug, $key)) {
                return $filters;
            }
        }

        return [];
    }
}
