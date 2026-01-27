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
#[Title('Laporan Penjualan Detail - Yala Computer')]
class SalesReport extends Component
{
    // Filter
    public $tanggalMulai;
    public $tanggalAkhir;
    public $periode = 'bulan_ini'; // hari_ini, minggu_ini, bulan_ini, manual

    /**
     * Inisialisasi komponen dengan periode default.
     */
    public function mount()
    {
        $this->aturPeriode('bulan_ini');
    }

    /**
     * Mengatur rentang tanggal berdasarkan preset periode.
     */
    public function aturPeriode($p)
    {
        $this->periode = $p;
        
        if ($p == 'hari_ini') {
            $this->tanggalMulai = now()->startOfDay()->format('Y-m-d');
            $this->tanggalAkhir = now()->endOfDay()->format('Y-m-d');
        } elseif ($p == 'minggu_ini') {
            $this->tanggalMulai = now()->startOfWeek()->format('Y-m-d');
            $this->tanggalAkhir = now()->endOfWeek()->format('Y-m-d');
        } elseif ($p == 'bulan_ini') {
            $this->tanggalMulai = now()->startOfMonth()->format('Y-m-d');
            $this->tanggalAkhir = now()->endOfMonth()->format('Y-m-d');
        }
    }

    /**
     * Render laporan dengan data statistik lengkap.
     */
    public function render()
    {
        $mulai = Carbon::parse($this->tanggalMulai)->startOfDay();
        $akhir = Carbon::parse($this->tanggalAkhir)->endOfDay();

        // 1. Ringkasan Utama
        $pesanan = Order::whereBetween('created_at', [$mulai, $akhir])
            ->where('status', '!=', 'cancelled')
            ->get();

        $totalPendapatan = $pesanan->sum('total_amount');
        $totalPesanan = $pesanan->count();
        $rataRataNilai = $totalPesanan > 0 ? $totalPendapatan / $totalPesanan : 0;

        // 2. Grafik Penjualan Harian
        $penjualanHarian = Order::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('SUM(total_amount) as total')
        )
            ->whereBetween('created_at', [$mulai, $akhir])
            ->where('status', '!=', 'cancelled')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Siapkan struktur data grafik (Label & Series)
        $grafikHarian = [
            'label' => $penjualanHarian->map(fn($item) => Carbon::parse($item->tanggal)->format('d M'))->toArray(),
            'data' => $penjualanHarian->map(fn($item) => $item->total)->toArray(),
        ];

        // 3. Produk Terlaris (Top 5)
        $produkTerlaris = OrderItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_qty'),
            DB::raw('SUM(price * quantity) as total_sales')
        )
            ->whereHas('order', function ($q) use ($mulai, $akhir) {
                $q->whereBetween('created_at', [$mulai, $akhir])
                    ->where('status', '!=', 'cancelled');
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // 4. Statistik Metode Pembayaran
        $metodeBayar = Order::select('payment_method', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$mulai, $akhir])
            ->where('status', '!=', 'cancelled')
            ->groupBy('payment_method')
            ->get();

        // Data Grafik Pembayaran
        $grafikBayar = [
            'label' => $metodeBayar->pluck('payment_method')->map(fn($m) => strtoupper($m))->toArray(),
            'data' => $metodeBayar->pluck('total')->toArray(),
        ];

        return view('livewire.reports.sales-report', [
            'totalPendapatan' => $totalPendapatan,
            'totalPesanan' => $totalPesanan,
            'rataRataNilai' => $rataRataNilai,
            'grafikHarian' => $grafikHarian,
            'produkTerlaris' => $produkTerlaris,
            'grafikBayar' => $grafikBayar,
        ]);
    }
}