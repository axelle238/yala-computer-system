<?php

namespace App\Livewire\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Layout('layouts.admin')]
#[Title('Laporan Penjualan - Yala Computer')]
class SalesReport extends Component
{
    public $startDate;
    public $endDate;
    public $period = 'this_month'; // today, this_week, this_month, custom

    public function mount()
    {
        $this->setPeriod('this_month');
    }

    public function updatedPeriod($val)
    {
        $this->setPeriod($val);
    }

    public function setPeriod($val)
    {
        $this->period = $val;
        switch ($val) {
            case 'today':
                $this->startDate = now()->startOfDay()->format('Y-m-d');
                $this->endDate = now()->endOfDay()->format('Y-m-d');
                break;
            case 'this_week':
                $this->startDate = now()->startOfWeek()->format('Y-m-d');
                $this->endDate = now()->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');
                break;
        }
    }

    public function render()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $orders = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'shipped', 'processing']) // Count as sales
            ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Group by day for chart
        $chartData = $orders->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('d M');
        })->map(function ($row) {
            return $row->sum('total_amount');
        });

        return view('livewire.reports.sales-report', [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'averageOrderValue' => $averageOrderValue,
            'chartLabels' => $chartData->keys(),
            'chartValues' => $chartData->values(),
            'recentOrders' => $orders->take(10)
        ]);
    }
}