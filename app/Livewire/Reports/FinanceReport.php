<?php

namespace App\Livewire\Reports;

use App\Models\Expense;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\Payroll;
use Carbon\Carbon;
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

        // 1. Revenue (Omzet)
        $revenue = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed') // Only completed sales
            ->sum('total_amount');

        // 2. COGS (HPP) - Complex Query
        // We assume 'out' transactions linked to sales represent COGS
        // Or strictly use InventoryTransaction where type='out' and unit_price > 0 (sales)
        $cogs = InventoryTransaction::whereBetween('created_at', [$start, $end])
            ->where('type', 'out')
            ->sum(function ($txn) {
                // If cogs column is populated, use it. Otherwise fallback (risky but needed if legacy data)
                return $txn->cogs > 0 ? $txn->cogs * $txn->quantity : 0;
            });
            // Optimization: The sum closure above runs in PHP memory (bad for large data).
            // Better: DB::raw sum(cogs * quantity)
            $cogsQuery = InventoryTransaction::whereBetween('created_at', [$start, $end])
                ->where('type', 'out');
                
            // Since cogs is unit cost in DB schema:
            // Need to sum (cogs * quantity)
            // But doing this in Eloquent collection is safer for now if data volume < 10k rows for this period. 
            // For rigorous app, use raw SQL. Let's use collection sum for simplicity in this context.
            $cogs = $cogsQuery->get()->sum(fn($t) => $t->cogs * $t->quantity);

        // 3. Gross Profit
        $grossProfit = $revenue - $cogs;

        // 4. Expenses
        $expenses = Expense::whereBetween('expense_date', [$start, $end])->get();
        $totalExpenses = $expenses->sum('amount');

        // 5. Payroll
        $payroll = Payroll::whereBetween('pay_date', [$start, $end])
            ->where('status', 'paid')
            ->sum('net_salary');

        // 6. Net Profit
        $netProfit = $grossProfit - $totalExpenses - $payroll;

        return view('livewire.reports.finance-report', [
            'revenue' => $revenue,
            'cogs' => $cogs,
            'grossProfit' => $grossProfit,
            'expenses' => $expenses,
            'totalExpenses' => $totalExpenses,
            'payroll' => $payroll,
            'netProfit' => $netProfit
        ]);
    }
}
