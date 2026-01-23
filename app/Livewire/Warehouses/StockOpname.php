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
                'quantity' => $diff, // Signed quantity
                'remaining_stock' => $realStock,
                'notes' => 'Stock Opname: ' . ($diff > 0 ? "Surplus +$diff" : "Defisit $diff"),
                'reference_number' => 'SO-' . date('Ymd'),
            ]);
        });

        $this->dispatch('notify', message: 'Stok berhasil disesuaikan!', type: 'success');
        unset($this->adjustments[$productId]);
    }

    public function deleteAdjustment($transactionId)
    {
        $transaction = InventoryTransaction::find($transactionId);
        
        if (!$transaction || $transaction->type !== 'adjustment') {
            $this->dispatch('notify', message: 'Transaksi tidak valid.', type: 'error');
            return;
        }

        $product = Product::find($transaction->product_id);
        
        if ($product) {
            // Revert stock: Subtract the transaction quantity
            // If we added 5 (qty=5), we subtract 5.
            // If we removed 5 (qty=-5), we subtract -5 (add 5).
            $product->decrement('stock_quantity', $transaction->quantity);
            
            $transaction->delete();
            $this->dispatch('notify', message: 'Penyesuaian dibatalkan.', type: 'success');
        }
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

        $history = InventoryTransaction::with('product', 'user')
            ->where('type', 'adjustment')
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.warehouses.stock-opname', [
            'products' => $products,
            'categories' => Category::all(),
            'history' => $history
        ]);
    }
}
