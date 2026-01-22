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

    public function deleteAdjustment($transactionId)
    {
        $transaction = InventoryTransaction::find($transactionId);
        
        if (!$transaction || $transaction->type !== 'adjustment') {
            $this->dispatch('notify', message: 'Transaksi tidak valid.', type: 'error');
            return;
        }

        $product = Product::find($transaction->product_id);
        
        if ($product) {
            // Revert stock
            // If notes say "Surplus +X", it means we added stock. To revert, we subtract.
            // If notes say "Defisit -X", it means we removed stock. To revert, we add.
            // Actually, we stored absolute quantity. We need to know direction.
            // But simpler: we stored the diff description. 
            // Better logic: Compare 'remaining_stock' with previous state? No.
            // Let's rely on the notes or just infer from context? 
            // Standard approach: store signed quantity or create a reversal transaction.
            // But here I'm deleting the log. So I should revert the effect.
            
            // "Surplus +5" -> Stock increased by 5. Revert: Decrease by 5.
            // "Defisit -5" -> Stock decreased by 5. Revert: Increase by 5.
            
            $isSurplus = str_contains($transaction->notes, 'Surplus');
            $qty = $transaction->quantity;

            if ($isSurplus) {
                $product->decrement('stock_quantity', $qty);
            } else {
                $product->increment('stock_quantity', $qty);
            }
            
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
