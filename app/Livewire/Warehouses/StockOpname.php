<?php

namespace App\Livewire\Warehouses;

use App\Models\Category;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Stock Opname - Yala Computer')]
class StockOpname extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $adjustments = []; // [product_id => real_stock]

    public function mount()
    {
        // No init logic needed for now
    }

    public function saveAdjustment($productId)
    {
        if (!isset($this->adjustments[$productId])) return;

        $realStock = intval($this->adjustments[$productId]);
        $product = Product::find($productId);

        if (!$product) return;

        $diff = $realStock - $product->stock_quantity;

        if ($diff == 0) {
            $this->dispatch('notify', message: 'Stok sudah sesuai.', type: 'info');
            return;
        }

        DB::transaction(function () use ($product, $realStock, $diff) {
            $product->update(['stock_quantity' => $realStock]);

            InventoryTransaction::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'adjustment',
                'quantity' => abs($diff),
                'remaining_stock' => $realStock,
                'notes' => 'Stock Opname: ' . ($diff > 0 ? "Surplus +$diff" : "Defisit $diff"),
                'reference_number' => 'SO-' . date('Ymd'),
            ]);
        });

        $this->dispatch('notify', message: 'Stok berhasil disesuaikan!', type: 'success');
        unset($this->adjustments[$productId]);
    }

    public function render()
    {
        $products = Product::with('category')
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function($q) {
                $q->where('category_id', $this->category);
            })
            ->orderBy('name')
            ->paginate(20);

        return view('livewire.warehouses.stock-opname', [
            'products' => $products,
            'categories' => Category::all()
        ]);
    }
}
