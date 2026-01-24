<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ServiceTicket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BusinessIntelligence
{
    /**
     * Get Sales Trend for Chart (Last 12 Months)
     */
    public function getSalesTrend()
    {
        $data = Order::select(
            DB::raw('sum(total_amount) as revenue'), 
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_year"),
            DB::raw('YEAR(created_at) as year, MONTH(created_at) as month')
        )
        ->where('status', 'completed')
        ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
        ->groupBy('year', 'month', 'month_year')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

        return $data;
    }

    /**
     * Get Top Selling Products
     */
    public function getTopProducts($limit = 5)
    {
        return OrderItem::select('product_id', DB::raw('sum(quantity) as total_qty'))
            ->whereHas('order', fn($q) => $q->where('status', 'completed'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take($limit)
            ->get();
    }

    /**
     * Inventory Forecasting using Simple Moving Average (SMA)
     * Predict days until stockout based on last 30 days sales.
     */
    public function getInventoryForecast()
    {
        $products = Product::where('stock_quantity', '>', 0)->get();
        $forecasts = [];

        foreach ($products as $product) {
            // Sales in last 30 days
            $soldLast30Days = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function($q) {
                    $q->where('status', 'completed')
                      ->where('created_at', '>=', now()->subDays(30));
                })
                ->sum('quantity');

            $dailyRunRate = $soldLast30Days / 30;

            if ($dailyRunRate > 0) {
                $daysUntilEmpty = $product->stock_quantity / $dailyRunRate;
                
                // Only alert if stockout is imminent (< 14 days)
                if ($daysUntilEmpty < 14) {
                    $forecasts[] = [
                        'product' => $product,
                        'daily_usage' => round($dailyRunRate, 2),
                        'days_left' => round($daysUntilEmpty),
                    ];
                }
            }
        }

        return collect($forecasts)->sortBy('days_left');
    }

    /**
     * Employee Performance Metrics
     */
    public function getEmployeePerformance()
    {
        // Sales Performance (Based on orders handled/referred if we had that, or just completed orders if they are sales)
        // For now, let's use Service Technicians performance
        
        $technicians = User::where('role', 'technician')->orWhere('role', 'admin')->get()->map(function($user) {
            $ticketsCompleted = ServiceTicket::where('technician_id', $user->id)
                ->where('status', 'completed')
                ->whereMonth('updated_at', now()->month)
                ->count();
            
            return [
                'name' => $user->name,
                'role' => $user->role,
                'metric' => 'Tickets Closed',
                'value' => $ticketsCompleted
            ];
        })->sortByDesc('value');

        return $technicians;
    }
}
