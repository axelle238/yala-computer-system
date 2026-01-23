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

        // 1. Product Revenue (Penjualan Barang)
        $productRevenue = InventoryTransaction::where('type', 'out')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(InventoryTransaction::raw('quantity * unit_price'));

        // 2. Product COGS (HPP Barang)
        $productCOGS = InventoryTransaction::where('type', 'out')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(InventoryTransaction::raw('quantity * cogs'));

        // 3. Service Revenue (Jasa Perbaikan)
        // Kita hitung dari Tiket yang SELESAI di bulan ini.
        // Revenue Jasa = Total Tagihan - Total Sparepart
        $finishedTickets = \App\Models\ServiceTicket::with('items')
            ->where('status', 'picked_up') // Hanya yang sudah lunas/diambil
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->get();

        $serviceRevenue = 0;
        foreach ($finishedTickets as $ticket) {
            $partsCost = $ticket->items->sum(fn($i) => $i->price * $i->quantity);
            // Jasa = Final Cost - Harga Part Jual
            $labor = max(0, $ticket->final_cost - $partsCost);
            $serviceRevenue += $labor;
            
            // Note: Sparepart yang terjual di service sudah masuk ke 'productRevenue' dan 'productCOGS' 
            // LEWAT mekanisme InventoryTransaction 'out' yang kita buat otomatis di Services\Form.php.
            // Jadi disini kita HANYA menghitung komponen JASA murni agar tidak double counting.
        }

        // 4. Gross Profit
        $grossProfit = ($productRevenue - $productCOGS) + $serviceRevenue;

        // 5. Expenses (Beban Operasional)
        // Pastikan kolom tanggal di tabel expenses konsisten (saya pakai expense_date atau created_at)
        // Cek schema expenses dulu, kalau belum ada saya default created_at
        $expenses = Expense::whereBetween('created_at', [$startDate, $endDate])->get();
        $totalExpenses = $expenses->sum('amount');

        // 6. Net Profit (Laba Bersih)
        $netProfit = $grossProfit - $totalExpenses;

        // 7. Trend Data (Daily Net Profit for Chart)
        $trendData = [];
        $daysInMonth = $startDate->daysInMonth;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dayDate = Carbon::createFromDate($this->year, $this->month, $i);
            
            $dayProdRev = InventoryTransaction::where('type', 'out')
                ->whereDate('created_at', $dayDate)
                ->sum(InventoryTransaction::raw('quantity * unit_price'));
                
            $dayProdCOGS = InventoryTransaction::where('type', 'out')
                ->whereDate('created_at', $dayDate)
                ->sum(InventoryTransaction::raw('quantity * cogs'));
            
            // Daily Service Revenue
            $dayServiceRev = 0;
            // Optimasi: query service per hari (bisa berat jika traffic tinggi, tapi oke untuk level ini)
            $dayTickets = \App\Models\ServiceTicket::with('items')
                ->where('status', 'picked_up')
                ->whereDate('updated_at', $dayDate)
                ->get();
            foreach ($dayTickets as $t) {
                $dayServiceRev += max(0, $t->final_cost - $t->items->sum(fn($i) => $i->price * $i->quantity));
            }

            $dayExpense = Expense::whereDate('created_at', $dayDate)->sum('amount');
            
            $trendData[] = [
                'day' => $i,
                'profit' => (($dayProdRev - $dayProdCOGS) + $dayServiceRev) - $dayExpense
            ];
        }

        return view('livewire.finance.profit-loss', [
            'productRevenue' => $productRevenue,
            'serviceRevenue' => $serviceRevenue,
            'cogs' => $productCOGS,
            'grossProfit' => $grossProfit,
            'expenses' => $expenses,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
            'trendData' => $trendData
        ]);
    }
}
