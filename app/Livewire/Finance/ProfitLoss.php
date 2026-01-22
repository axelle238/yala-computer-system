<?php

namespace App\Livewire\Finance;

use App\Models\Expense;
use App\Models\InventoryTransaction;
use App\Models\User;
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
        $this->month = date('m');
        $this->year = date('Y');
    }

    public function render()
    {
        // 1. Revenue (Omzet Penjualan)
        // Ambil semua transaksi 'out' bulan ini
        $salesTransactions = InventoryTransaction::where('type', 'out')
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->with('product')
            ->get();

        $revenue = 0;
        $cogs = 0; // HPP (Harga Pokok Penjualan)

        foreach ($salesTransactions as $trx) {
            $revenue += $trx->quantity * $trx->product->sell_price;
            $cogs += $trx->quantity * $trx->product->buy_price;
        }

        $grossProfit = $revenue - $cogs;

        // 2. Operational Expenses (Biaya Operasional)
        $operationalExpenses = Expense::whereMonth('expense_date', $this->month)
            ->whereYear('expense_date', $this->year)
            ->sum('amount');

        // 3. Payroll Expenses (Gaji Pegawai)
        // Hitung estimasi gaji + komisi
        $employees = User::where('role', 'employee')->get();
        $payrollExpenses = 0;

        foreach ($employees as $user) {
            // Hitung sales pribadi untuk komisi
            $personalSales = InventoryTransaction::where('user_id', $user->id)
                ->where('type', 'out')
                ->whereMonth('created_at', $this->month)
                ->whereYear('created_at', $this->year)
                ->with('product')
                ->get()
                ->sum(function($t) { return $t->quantity * $t->product->sell_price; });
            
            $commission = $personalSales * 0.01;
            $payrollExpenses += ($user->base_salary + $commission);
        }

        // 4. Net Profit
        $totalExpenses = $operationalExpenses + $payrollExpenses;
        $netProfit = $grossProfit - $totalExpenses;

        return view('livewire.finance.profit-loss', [
            'revenue' => $revenue,
            'cogs' => $cogs,
            'grossProfit' => $grossProfit,
            'operationalExpenses' => $operationalExpenses,
            'payrollExpenses' => $payrollExpenses,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit
        ]);
    }
}
