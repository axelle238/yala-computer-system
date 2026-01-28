<?php

namespace App\Livewire\Finance;

use App\Models\Expense;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
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
        $startDate = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($this->year, $this->month, 1)->endOfMonth();

        // 1. REVENUE (Pendapatan)
        // Penjualan yang sudah Selesai (Completed)
        $orders = Order::with('item.produk')
            ->where('status', 'completed')
            ->whereBetween('updated_at', [$startDate, $endDate]) // Pakai updated_at karena completed date
            ->get();

        $totalRevenue = $orders->sum('total_amount');

        // 2. COGS (Harga Pokok Penjualan)
        // Mengambil total HPP dari transaksi inventaris keluar di periode ini
        $cogs = InventoryTransaction::where('type', 'out')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('quantity * cogs'));

        // Jika data cogs di InventoryTransaction kosong (transaksi lama), gunakan fallback ke perhitungan manual
        if ($cogs <= 0) {
            foreach ($orders as $order) {
                foreach ($order->item as $item) {
                    $buyPrice = $item->produk->buy_price ?? 0;
                    $cogs += ($buyPrice * $item->quantity);
                }
            }
        }

        $grossProfit = $totalRevenue - $cogs;

        // 3. EXPENSES (Biaya Operasional)
        $operationalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');

        // Gaji Karyawan
        $payrollExpenses = Payroll::where('period_month', sprintf('%02d-%d', $this->month, $this->year))->sum('net_salary');

        $totalExpenses = $operationalExpenses + $payrollExpenses;

        // 4. NET PROFIT
        $netProfit = $grossProfit - $totalExpenses;

        return view('livewire.finance.profit-loss', [
            'revenue' => $totalRevenue,
            'cogs' => $cogs,
            'grossProfit' => $grossProfit,
            'expenses' => [
                'operational' => $operationalExpenses,
                'payroll' => $payrollExpenses,
                'total' => $totalExpenses,
            ],
            'netProfit' => $netProfit,
        ]);
    }
}
