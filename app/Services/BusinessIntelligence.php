<?php

namespace App\Services;

use App\Models\CashTransaction;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\Product;
use App\Models\ServiceTicket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BusinessIntelligence
{
    /**
     * Menghitung Laporan Laba Rugi (Profit & Loss)
     */
    public function getProfitLoss($month, $year)
    {
        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // 1. REVENUE (Pendapatan Kotor)
        // Dari Penjualan Langsung (POS/Online)
        $salesRevenue = Order::where('status', 'completed')
            ->whereBetween('paid_at', [$start, $end])
            ->sum('total_amount');

        // Dari Servis (Yang dibayar terpisah/non-order flow)
        // Asumsi: Servis yang sudah picked_up dianggap lunas (revenue diakui saat selesai)
        // Perhatian: Jika pembayaran servis masuk ke CashTransaction sebagai 'service_payment', kita bisa ambil dari sana untuk akurasi cash basis.
        // Mari gunakan CashTransaction untuk pendekatan 'Cash Basis' yang lebih riil.

        $incomeTransactions = CashTransaction::where('type', 'in')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalRevenue = $incomeTransactions->sum('amount');
        $revenueDetails = $incomeTransactions->groupBy('category')->map(fn ($row) => $row->sum('amount'));

        // 2. COGS (Harga Pokok Penjualan) - Biaya Modal Barang yang Terjual
        // Kita hitung dari Inventory Transaction tipe 'out' pada periode ini
        $cogs = InventoryTransaction::where('type', 'out')
            ->whereBetween('created_at', [$start, $end])
            ->select(DB::raw('SUM(quantity * cogs) as total_cogs'))
            ->value('total_cogs') ?? 0;

        // 3. GROSS PROFIT (Laba Kotor)
        $grossProfit = $totalRevenue - $cogs;

        // 4. OPERATING EXPENSES (Beban Operasional)
        // Gaji, Listrik, Air, dll (Cash Transaction OUT)
        $expenses = CashTransaction::where('type', 'out')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalExpenses = $expenses->sum('amount');
        $expenseDetails = $expenses->groupBy('category')->map(fn ($row) => $row->sum('amount'));

        // 5. NET PROFIT (Laba Bersih)
        $netProfit = $grossProfit - $totalExpenses;

        return [
            'period' => $start->format('F Y'),
            'revenue' => [
                'total' => $totalRevenue,
                'breakdown' => $revenueDetails,
            ],
            'cogs' => $cogs,
            'gross_profit' => $grossProfit,
            'expenses' => [
                'total' => $totalExpenses,
                'breakdown' => $expenseDetails,
            ],
            'net_profit' => $netProfit,
            'margin_percentage' => $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0,
        ];
    }

    /**
     * Analisis Performa Teknisi
     */
    public function getTechnicianStats($month, $year)
    {
        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        return ServiceTicket::with('technician')
            ->whereBetween('updated_at', [$start, $end]) // Use updated_at or finished_at
            ->whereIn('status', ['ready', 'picked_up'])
            ->select('technician_id', DB::raw('count(*) as total_tickets'), DB::raw('sum(final_cost) as total_revenue'))
            ->groupBy('technician_id')
            ->get()
            ->map(function ($stat) {
                return [
                    'name' => $stat->technician->name ?? 'Unassigned',
                    'tickets' => $stat->total_tickets,
                    'revenue' => $stat->total_revenue,
                ];
            });
    }

    /**
     * Analisis Stok (Fast vs Slow Moving)
     * Limit: Top 10
     */
    public function getStockAnalysis()
    {
        // Fast Moving (Berdasarkan qty keluar 30 hari terakhir)
        $fastMoving = InventoryTransaction::where('type', 'out')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->with('product')
            ->get();

        // Low Stock Alert
        $lowStock = Product::where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'min_stock_alert')
            ->where('stock_quantity', '>', 0) // Masih ada tapi dikit
            ->limit(10)
            ->get();

        // Dead Stock (Barang ada stok, tapi tidak ada transaksi keluar 90 hari)
        // Query ini agak berat, kita simplifikasi: Ambil barang stok > 0 yang tidak ada di inventory transaction out 90 hari
        $activeProductIds = InventoryTransaction::where('type', 'out')
            ->where('created_at', '>=', Carbon::now()->subDays(90))
            ->pluck('product_id')
            ->toArray();

        $deadStock = Product::whereNotIn('id', $activeProductIds)
            ->where('stock_quantity', '>', 0)
            ->where('is_active', true)
            ->limit(10)
            ->get();

        return [
            'fast_moving' => $fastMoving,
            'low_stock' => $lowStock,
            'dead_stock' => $deadStock,
        ];
    }
}
