<?php

namespace App\Livewire\Reports;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Pusat Analisis & Laporan - Yala Computer')]
class Index extends Component
{
    public $range = '30_days'; // 7_days, 30_days, this_month, last_month, this_year

    public function render()
    {
        $startDate = match ($this->range) {
            '7_days' => now()->subDays(7),
            'this_month' => now()->startOfMonth(),
            'last_month' => now()->subMonth()->startOfMonth(),
            'this_year' => now()->startOfYear(),
            default => now()->subDays(30)
        };

        $endDate = $this->range === 'last_month' ? now()->subMonth()->endOfMonth() : now();

        // 1. Top Selling Products (Pareto)
        $topProducts = InventoryTransaction::where('type', 'out')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('product_id', DB::raw('sum(quantity) as total_qty'), DB::raw('sum(quantity * unit_price) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->with('product')
            ->take(5)
            ->get();

        // 2. Dead Stock Analysis (Items no sales in 90 days)
        // Logic: Product where last 'out' transaction is > 90 days ago OR no 'out' transaction
        // Simplify for performance: Get all active products with stock > 0, filter those not in recent transactions
        $soldProductIds = InventoryTransaction::where('type', 'out')
            ->where('created_at', '>=', now()->subDays(90))
            ->pluck('product_id')
            ->unique();

        $deadStocks = Product::whereNotIn('id', $soldProductIds)
            ->where('stock_quantity', '>', 0)
            ->where('is_active', true)
            ->take(10)
            ->get();

        // 3. Category Performance
        $categoryPerformance = InventoryTransaction::where('type', 'out')
            ->whereBetween('inventory_transactions.created_at', [$startDate, $endDate])
            ->join('products', 'inventory_transactions.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('sum(inventory_transactions.quantity * inventory_transactions.unit_price) as revenue'))
            ->groupBy('categories.name')
            ->get();

        return view('livewire.reports.index', [
            'topProducts' => $topProducts,
            'deadStocks' => $deadStocks,
            'categoryPerformance' => $categoryPerformance,
        ]);
    }
}
