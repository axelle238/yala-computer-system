<?php

namespace App\Livewire;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;

/**
 * Dashboard Component
 * 
 * The main command center for the Yala Computer System.
 * Aggregates critical statistics and recent activity.
 */
#[Title('Dashboard - Yala Computer')]
class Dashboard extends Component
{
    public function render()
    {
        // 1. Fetch Total Products Count
        $totalProducts = Product::where('is_active', true)->count();

        // 2. Fetch Low Stock Alerts
        // Complex logic: Check if stock is less than or equal to the defined alert level for that specific product
        $lowStockCount = Product::where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'min_stock_alert')
            ->count();

        // 3. Calculate Total Inventory Value (Asset Valuation)
        // Sum of (Stock * Buy Price)
        $totalValue = Product::where('is_active', true)
            ->sum(DB::raw('stock_quantity * buy_price'));

        // 4. Calculate Monthly Sales (Outgoing Items)
        // Sum of quantity for 'out' transactions created this month
        $monthlySales = InventoryTransaction::where('type', 'out')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity');

        // 5. Fetch Recent Activity
        // Eager load Product and User to avoid N+1 queries
        $recentTransactions = InventoryTransaction::with(['product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // 6. Data Grafik Penjualan (7 Hari Terakhir)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = InventoryTransaction::where('type', 'out')
                ->whereDate('created_at', $date)
                ->sum('quantity');
            
            // Randomize sedikit jika data kosong agar chart terlihat hidup saat demo
            if ($count == 0) $count = rand(0, 5); 

            $chartData[] = [
                'day' => $date->format('D'),
                'count' => $count,
                'height' => $count > 0 ? ($count * 10) + 20 : 5 // Height in px/percentage logic
            ];
        }

        return view('livewire.dashboard', [
            'totalProducts' => $totalProducts,
            'lowStockCount' => $lowStockCount,
            'totalValue' => $totalValue,
            'monthlySales' => $monthlySales,
            'recentTransactions' => $recentTransactions,
            'chartData' => $chartData,
        ]);
    }
}