<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Title;

#[Title('Manajemen Produk - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $categoryFilter = '';

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Reset pagination saat melakukan pencarian
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query Builder untuk Produk
        $products = Product::query()
            ->with(['category', 'supplier']) // Eager Load untuk performa
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('sku', 'like', '%' . $this->search . '%')
                        ->orWhere('barcode', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $categories = Category::all();

        return view('livewire.products.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
