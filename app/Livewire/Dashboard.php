<?php

namespace App\Livewire;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\ServiceTicket;
use App\Services\BusinessIntelligence;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Executive Dashboard - Yala Computer')]
class Dashboard extends Component
{
    public function render()
    {
        $bi = new BusinessIntelligence();

        // 1. Core Stats (Cached 60s)
        $stats = Cache::remember('dashboard_core_stats', 60, function () {
            return [
                'totalValue' => Product::where('is_active', true)->sum(DB::raw('stock_quantity * buy_price')),
                'monthlyRevenue' => \App\Models\Order::where('status', 'completed')
                    ->whereMonth('created_at', now()->month)
                    ->sum('total_amount'),
                'lowStock' => Product::whereColumn('stock_quantity', '<=', 'min_stock_alert')->count(),
                'activeTickets' => ServiceTicket::whereNotIn('status', ['completed', 'cancelled', 'picked_up'])->count(),
            ];
        });

        // 2. Business Intelligence Data (Cached 5 mins)
        $analytics = Cache::remember('dashboard_bi_data', 300, function () use ($bi) {
            return [
                'salesTrend' => $bi->getSalesTrend(),
                'topProducts' => $bi->getTopProducts(),
                'forecast' => $bi->getInventoryForecast()->take(5), // Top 5 critical items
                'technicians' => $bi->getEmployeePerformance(),
            ];
        });

        // 3. Recent Activity (Realtime)
        $recentTransactions = InventoryTransaction::with(['product', 'user'])->latest()->take(5)->get();

        return view('livewire.dashboard', [
            'stats' => $stats,
            'analytics' => $analytics,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}