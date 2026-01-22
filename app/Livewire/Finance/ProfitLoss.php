<?php

namespace App\Livewire\Finance;

use App\Models\Expense;
use App\Models\InventoryTransaction;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Laporan Laba Rugi - Yala Computer')]
class ProfitLoss extends Component
{
    public $month;
    public $year;

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }

    public function render()
    {
        $startDate = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($this->year, $this->month, 1)->endOfMonth();

        // 1. Revenue (Pendapatan Penjualan)
        // Note: Using snapshot 'unit_price' from transactions for accuracy
        $revenue = InventoryTransaction::where('type', 'out')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(InventoryTransaction::raw('quantity * unit_price'));

        // 2. COGS (HPP)
        // Using snapshot 'cogs' from transactions
        $cogs = InventoryTransaction::where('type', 'out')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(InventoryTransaction::raw('quantity * cogs'));

        // 3. Gross Profit
        $grossProfit = $revenue - $cogs;

        // 4. Expenses (Beban Operasional)
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->get();
        $totalExpenses = $expenses->sum('amount');

        // 5. Net Profit (Laba Bersih)
        $netProfit = $grossProfit - $totalExpenses;

        // 6. Trend Data (Daily Net Profit for Chart)
        $trendData = [];
        $daysInMonth = $startDate->daysInMonth;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dayDate = Carbon::createFromDate($this->year, $this->month, $i);
            
            $dayRevenue = InventoryTransaction::where('type', 'out')
                ->whereDate('created_at', $dayDate)
                ->sum(InventoryTransaction::raw('quantity * unit_price'));
                
            $dayCOGS = InventoryTransaction::where('type', 'out')
                ->whereDate('created_at', $dayDate)
                ->sum(InventoryTransaction::raw('quantity * cogs'));
                
            $dayExpense = Expense::whereDate('expense_date', $dayDate)->sum('amount');
            
            $trendData[] = [
                'day' => $i,
                'profit' => ($dayRevenue - $dayCOGS) - $dayExpense
            ];
        }

        return view('livewire.finance.profit-loss', [
            'revenue' => $revenue,
            'cogs' => $cogs,
            'grossProfit' => $grossProfit,
            'expenses' => $expenses,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
            'trendData' => $trendData
        ]);
    }
}
