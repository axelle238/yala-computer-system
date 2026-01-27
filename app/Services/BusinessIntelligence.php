<?php

namespace App\Services;

use App\Models\CashTransaction;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\Product;
use App\Models\ServiceTicket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Layanan Intelijen Bisnis (Business Intelligence)
 * Menangani logika perhitungan statistik, laba rugi, dan analisis stok.
 */
class BusinessIntelligence
{
    /**
     * Menghitung Laporan Laba Rugi (Profit & Loss)
     */
    public function ambilLabaRugi($bulan, $tahun)
    {
        $awal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $akhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        // 1. PENDAPATAN (Revenue)
        // Menggunakan CashTransaction tipe 'masuk' untuk pendekatan 'Cash Basis' yang riil.
        $transaksiMasuk = CashTransaction::where('type', 'in')
            ->whereBetween('created_at', [$awal, $akhir])
            ->get();

        $totalPendapatan = $transaksiMasuk->sum('amount');
        $detailPendapatan = $transaksiMasuk->groupBy('category')->map(fn ($baris) => $baris->sum('amount'));

        // 2. HPP (Harga Pokok Penjualan) - Biaya Modal Barang yang Terjual
        // Dihitung dari Inventory Transaction tipe 'keluar' (out) pada periode ini
        $hpp = InventoryTransaction::where('type', 'out')
            ->whereBetween('created_at', [$awal, $akhir])
            ->select(DB::raw('SUM(quantity * cogs) as total_hpp'))
            ->value('total_hpp') ?? 0;

        // 3. LABA KOTOR (Gross Profit)
        $labaKotor = $totalPendapatan - $hpp;

        // 4. BEBAN OPERASIONAL (Operating Expenses)
        // Gaji, Listrik, Air, dll (Cash Transaction KELUAR)
        $beban = CashTransaction::where('type', 'out')
            ->whereBetween('created_at', [$awal, $akhir])
            ->get();

        $totalBeban = $beban->sum('amount');
        $detailBeban = $beban->groupBy('category')->map(fn ($baris) => $baris->sum('amount'));

        // 5. LABA BERSIH (Net Profit)
        $labaBersih = $labaKotor - $totalBeban;

        return [
            'periode' => $awal->format('F Y'),
            'pendapatan' => [
                'total' => $totalPendapatan,
                'rincian' => $detailPendapatan,
            ],
            'hpp' => $hpp,
            'laba_kotor' => $labaKotor,
            'beban' => [
                'total' => $totalBeban,
                'rincian' => $detailBeban,
            ],
            'laba_bersih' => $labaBersih,
            'persentase_margin' => $totalPendapatan > 0 ? ($labaBersih / $totalPendapatan) * 100 : 0,
        ];
    }

    /**
     * Analisis Performa Teknisi
     */
    public function ambilStatistikTeknisi($bulan, $tahun)
    {
        $awal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $akhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        return ServiceTicket::with('technician')
            ->whereBetween('updated_at', [$awal, $akhir])
            ->whereIn('status', ['ready', 'picked_up'])
            ->select('technician_id', DB::raw('count(*) as total_tiket'), DB::raw('sum(final_cost) as total_pendapatan'))
            ->groupBy('technician_id')
            ->get()
            ->map(function ($stat) {
                return [
                    'nama' => $stat->technician->name ?? 'Tanpa Nama',
                    'tiket' => $stat->total_tiket,
                    'pendapatan' => $stat->total_pendapatan,
                ];
            });
    }

    /**
     * Analisis Stok (Fast vs Slow Moving)
     */
    public function ambilAnalisisStok()
    {
        // Fast Moving (Berdasarkan qty keluar 30 hari terakhir)
        $lakuPesat = InventoryTransaction::where('type', 'out')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select('product_id', DB::raw('SUM(quantity) as total_terjual'))
            ->groupBy('product_id')
            ->orderByDesc('total_terjual')
            ->limit(10)
            ->with('product')
            ->get();

        // Stok Menipis (Low Stock Alert)
        $stokMenipis = Product::where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'min_stock_alert')
            ->where('stock_quantity', '>', 0)
            ->limit(10)
            ->get();

        // Stok Mati (Dead Stock - Barang ada stok, tapi tidak terjual 90 hari)
        $idProdukAktif = InventoryTransaction::where('type', 'out')
            ->where('created_at', '>=', Carbon::now()->subDays(90))
            ->pluck('product_id')
            ->toArray();

        $stokMati = Product::whereNotIn('id', $idProdukAktif)
            ->where('stock_quantity', '>', 0)
            ->where('is_active', true)
            ->limit(10)
            ->get();

        return [
            'laku_pesat' => $lakuPesat,
            'stok_menipis' => $stokMenipis,
            'stok_mati' => $stokMati,
        ];
    }
}