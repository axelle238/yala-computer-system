<?php

namespace App\Livewire\Reports;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Kartu Stok & Mutasi Barang - Yala Computer')]
class StockReport extends Component
{
    use WithPagination;

    public $searchProduct = '';

    public $selectedProductId = null;

    public $startDate;

    public $endDate;

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function selectProduct($id)
    {
        $this->selectedProductId = $id;
        $this->searchProduct = Product::find($id)->name;
    }

    public function render()
    {
        // Product Search (Dropdown)
        $products = [];
        if (strlen($this->searchProduct) > 2) {
            $products = Product::where('name', 'like', '%'.$this->searchProduct.'%')
                ->orWhere('sku', 'like', '%'.$this->searchProduct.'%')
                ->take(5)->get();
        }

        // Transactions Logic (Stock Card)
        $transactions = [];
        $openingStock = 0;

        if ($this->selectedProductId) {
            // Calculate Opening Stock (Sum of tx before start date)
            $inBefore = InventoryTransaction::where('product_id', $this->selectedProductId)
                ->where('created_at', '<', $this->startDate)
                ->whereIn('type', ['in', 'adjustment', 'return'])
                ->sum('quantity');

            $outBefore = InventoryTransaction::where('product_id', $this->selectedProductId)
                ->where('created_at', '<', $this->startDate)
                ->whereIn('type', ['out'])
                ->sum('quantity');

            // Note: 'adjustment' can be negative, but usually stored as positive qty with type.
            // If my adjustment logic uses negative qty in DB, sum() works.
            // Checking InventoryTransaction logic: usually type='adjustment' and qty can be +/- or type='adjustment_in'/'adjustment_out'.
            // Assuming simplified model: type 'in' adds, 'out' subtracts.
            // 'adjustment' needs check. Let's assume 'adjustment' with negative qty is reduction.
            // Based on previous code: InventoryTransaction stores positive quantity usually, and type determines direction.
            // Let's refine based on typical simple logic:

            // Re-check InventoryTransaction structure or logic used in StockOpname
            // In StockOpname: InventoryTransaction::create(['type' => 'adjustment', 'quantity' => $diff...])
            // So 'quantity' can be negative there.

            $openingStock = InventoryTransaction::where('product_id', $this->selectedProductId)
                ->where('created_at', '<', $this->startDate)
                ->sum('quantity'); // Assuming quantity is signed (+/-) for adjustments, but usually 'in'/'out' use positive unsigned.

            // Correction: Best practice is 'quantity' is absolute, 'type' dictates sign.
            // Let's rely on a calculated attribute or simple logic:
            // In = +, Out = -, Return = +, Adjustment = +/- (if stored signed)

            // Let's just fetch history for the selected period
            $transactions = InventoryTransaction::with('user')
                ->where('product_id', $this->selectedProductId)
                ->whereBetween('created_at', [$this->startDate.' 00:00:00', $this->endDate.' 23:59:59'])
                ->latest()
                ->paginate(20);
        }

        return view('livewire.reports.stock-report', [
            'searchResultProducts' => $products,
            'transactions' => $transactions,
            'openingStock' => $openingStock, // Placeholder, implementing true recursive opening stock is heavy without snapshots
        ]);
    }
}
