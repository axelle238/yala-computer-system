<?php

namespace App\Livewire\Reports;

use App\Models\Expense;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\Payroll;
use App\Models\ServiceTicket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Laporan Keuangan & Laba Rugi - Yala Computer')]
class FinanceReport extends Component
{
    public $startDate;
    public $endDate;
    public $period = 'this_month'; // today, this_month, last_month, this_year

    public function mount()
    {
        $this->setPeriod('this_month');
    }

    public function setPeriod($val)
    {
        $this->period = $val;
        switch ($val) {
            case 'today':
                $this->startDate = Carbon::today()->format('Y-m-d');
                $this->endDate = Carbon::today()->format('Y-m-d');
                break;
            case 'this_month':
                $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $this->startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'this_year':
                $this->startDate = Carbon::now()->startOfYear()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
        }
    }

    public function render()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        // 1. Revenue (Sales + Service)
        $salesRevenue = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'shipped', 'processing']) // Revenue recognized on process/ship/complete
            ->sum('total_amount');

        $serviceRevenue = ServiceTicket::whereBetween('updated_at', [$start, $end])
            ->where('status', 'picked_up') // Completed service
            ->sum('final_cost');

        $totalRevenue = $salesRevenue + $serviceRevenue;

        // 2. COGS (HPP) - From Inventory Out (Sales + Service Parts)
        // Optimization: Use SQL sum for performance
        $cogs = InventoryTransaction::whereBetween('created_at', [$start, $end])
            ->where('type', 'out')
            ->sum(DB::raw('quantity * cogs'));

        // 3. Gross Profit
        $grossProfit = $totalRevenue - $cogs;

        // 4. Expenses (Operational + Payroll)
        $operationalExpenses = Expense::whereBetween('expense_date', [$start, $end])->get();
        $totalOpExpense = $operationalExpenses->sum('amount');

        $payrollExpense = Payroll::whereBetween('pay_date', [$start, $end])
            ->where('status', 'paid')
            ->sum('net_salary');

        $totalExpenses = $totalOpExpense + $payrollExpense;

        // 5. Net Profit
        $netProfit = $grossProfit - $totalExpenses;

        // 6. Chart Data (Daily Trend)
        $chartData = $this->getChartData($start, $end);

        return view('livewire.reports.finance-report', [
            'salesRevenue' => $salesRevenue,
            'serviceRevenue' => $serviceRevenue,
            'totalRevenue' => $totalRevenue,
            'cogs' => $cogs,
            'grossProfit' => $grossProfit,
            'operationalExpenses' => $operationalExpenses,
            'totalOpExpense' => $totalOpExpense,
            'payrollExpense' => $payrollExpense,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
            'chartData' => $chartData
        ]);
    }

    private function getChartData($start, $end)
    {
        // Group by day
        $orders = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'shipped', 'processing'])
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        $services = ServiceTicket::selectRaw('DATE(updated_at) as date, SUM(final_cost) as total')
            ->whereBetween('updated_at', [$start, $end])
            ->where('status', 'picked_up')
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        // Merge dates
        $dates = $orders->keys()->merge($services->keys())->unique()->sort();
        
        $data = [];
        foreach ($dates as $date) {
            $data[] = [
                'date' => Carbon::parse($date)->format('d M'),
                'revenue' => ($orders[$date] ?? 0) + ($services[$date] ?? 0)
            ];
        }

        return $data;
    }
}
