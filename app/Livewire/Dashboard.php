<?php

namespace App\Livewire;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\ServiceTicket;
use App\Models\Order;
use App\Services\BusinessIntelligence;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
#[Title('Executive Dashboard - Yala Computer')]
class Dashboard extends Component
{
    public function render()
    {
        $bi = new BusinessIntelligence();
        $month = now()->month;
        $year = now()->year;

        // 1. Core Stats (Cached 60s for performance)
        $stats = Cache::remember('dashboard_core_stats', 60, function () use ($month, $year, $bi) {
            $pl = $bi->getProfitLoss($month, $year);
            
            return [
                'revenue' => $pl['revenue']['total'],
                'net_profit' => $pl['net_profit'],
                'active_tickets' => ServiceTicket::whereNotIn('status', ['cancelled', 'picked_up'])->count(),
                'orders_today' => Order::whereDate('created_at', today())->count(),
            ];
        });

        // 2. BI Analysis (Cached 5 mins)
        $analysis = Cache::remember('dashboard_analysis', 300, function () use ($bi) {
            return $bi->getStockAnalysis(); // returns fast_moving, low_stock, dead_stock
        });

        return view('livewire.dashboard', [
            'stats' => $stats,
            'analysis' => $analysis,
        ]);
    }
}
