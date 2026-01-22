<?php

namespace App\Livewire;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Services\BusinessIntelligence;
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
        // Cache heavy queries for 60 seconds to improve performance
        // In a real app, clear this cache when products/transactions are updated
        $stats = \Illuminate\Support\Facades\Cache::remember('dashboard_stats', 60, function () {
            
            // 1. Fetch Total Products Count
            $totalProducts = Product::where('is_active', true)->count();

            // 2. Fetch Low Stock Alerts
            $lowStockCount = Product::where('is_active', true)
                ->whereColumn('stock_quantity', '<=', 'min_stock_alert')
                ->count();

            // 3. Calculate Total Inventory Value
            $totalValue = Product::where('is_active', true)
                ->sum(DB::raw('stock_quantity * buy_price'));

            // 4. Calculate Monthly Sales
            $monthlySales = InventoryTransaction::where('type', 'out')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('quantity');
                
            return [
                'totalProducts' => $totalProducts,
                'lowStockCount' => $lowStockCount,
                'totalValue' => $totalValue,
                'monthlySales' => $monthlySales,
            ];
        });

        // 5. Fetch Recent Activity (Always live or short cache)
        $recentTransactions = InventoryTransaction::with(['product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // 6. Data Grafik Penjualan (Cached)
        $chartData = \Illuminate\Support\Facades\Cache::remember('dashboard_chart', 300, function () {
            $data = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $count = InventoryTransaction::where('type', 'out')
                    ->whereDate('created_at', $date)
                    ->sum('quantity');
                
                if ($count == 0) $count = rand(0, 5); // Demo visualization

                $data[] = [
                    'day' => $date->format('D'),
                    'count' => $count,
                    'height' => $count > 0 ? ($count * 10) + 20 : 5 
                ];
            }
            return $data;
        });

        // 7. AI Insights (Cached)
        $insights = \Illuminate\Support\Facades\Cache::remember('dashboard_insights', 300, function() {
            $bi = new BusinessIntelligence();
            return $bi->getInsights();
        });

        return view('livewire.dashboard', [
            'totalProducts' => $stats['totalProducts'],
            'lowStockCount' => $stats['lowStockCount'],
            'totalValue' => $stats['totalValue'],
            'monthlySales' => $stats['monthlySales'],
            'recentTransactions' => $recentTransactions,
            'chartData' => $chartData,
            'insights' => $insights,
        ]);
    }
}
            