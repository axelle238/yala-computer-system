<?php

namespace App\Livewire\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Laporan Penjualan Detail')]
class SalesReport extends Component
{
    public $startDate;

    public $endDate;

    public $period = 'this_month';

    public function mount()
    {
        $this->setPeriod('this_month');
    }

    public function setPeriod($period)
    {
        $this->period = $period;
        if ($period == 'today') {
            $this->startDate = now()->startOfDay()->format('Y-m-d');
            $this->endDate = now()->endOfDay()->format('Y-m-d');
        } elseif ($period == 'this_week') {
            $this->startDate = now()->startOfWeek()->format('Y-m-d');
            $this->endDate = now()->endOfWeek()->format('Y-m-d');
        } elseif ($period == 'this_month') {
            $this->startDate = now()->startOfMonth()->format('Y-m-d');
            $this->endDate = now()->endOfMonth()->format('Y-m-d');
        }
    }

    public function render()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        // 1. Total Stats
        $orders = Order::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // 2. Daily Chart Data
        $dailySales = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
            ->whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 3. Top Products
        $topProducts = OrderItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_qty'),
            DB::raw('SUM(price * quantity) as total_sales')
        )
            ->whereHas('order', function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])
                    ->where('status', '!=', 'cancelled');
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // 4. Payment Methods
        $paymentStats = Order::select('payment_method', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->groupBy('payment_method')
            ->get();

        return view('livewire.reports.sales-report', [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'avgOrderValue' => $avgOrderValue,
            'dailySales' => $dailySales,
            'topProducts' => $topProducts,
            'paymentStats' => $paymentStats,
        ]);
    }
}
