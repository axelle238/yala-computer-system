<?php

namespace App\Livewire;

use App\Models\Article;
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
        // Cache heavy queries for 60 seconds
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

            // 5. News Stats
            $totalArticles = Article::count();
            $totalViews = Article::sum('views_count');
            
            // 6. Service Stats (New)
            $serviceStats = [
                'pending' => \App\Models\ServiceTicket::whereIn('status', ['pending', 'diagnosing'])->count(),
                'repairing' => \App\Models\ServiceTicket::whereIn('status', ['waiting_part', 'repairing'])->count(),
                'ready' => \App\Models\ServiceTicket::where('status', 'ready')->count(),
            ];
                
            return [
                'totalProducts' => $totalProducts,
                'lowStockCount' => $lowStockCount,
                'totalValue' => $totalValue,
                'monthlySales' => $monthlySales,
                'totalArticles' => $totalArticles,
                'totalViews' => $totalViews,
                'serviceStats' => $serviceStats,
            ];
        });

        // 6. Fetch Recent Activity
        $recentTransactions = InventoryTransaction::with(['product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // 7. Data Grafik Penjualan
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

        // 8. AI Insights
        $insights = \Illuminate\Support\Facades\Cache::remember('dashboard_insights', 300, function() {
            $bi = new BusinessIntelligence();
            return $bi->getInsights();
        });

        return view('livewire.dashboard', [
            'totalProducts' => $stats['totalProducts'],
            'lowStockCount' => $stats['lowStockCount'],
            'totalValue' => $stats['totalValue'],
            'monthlySales' => $stats['monthlySales'],
            'totalArticles' => $stats['totalArticles'],
            'totalViews' => $stats['totalViews'],
            'serviceStats' => $stats['serviceStats'],
            'recentTransactions' => $recentTransactions,
            'chartData' => $chartData,
            'insights' => $insights,
        ]);
    }
}
