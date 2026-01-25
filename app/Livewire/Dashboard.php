<?php

namespace App\Livewire;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\ServiceTicket;
use App\Models\Order;
use App\Models\PcAssembly;
use App\Models\Quotation;
use App\Services\BusinessIntelligence;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
#[Title('Dashboard Eksekutif - Yala Computer')]
class Dashboard extends Component
{
    public function render()
    {
        $intelBisnis = new BusinessIntelligence();
        $bulan = now()->month;
        $tahun = now()->year;

        // 1. Statistik Inti (Cache 60 detik untuk performa)
        $statistik = Cache::remember('dashboard_core_stats', 60, function () use ($bulan, $tahun, $intelBisnis) {
            $labaRugi = $intelBisnis->getProfitLoss($bulan, $tahun);
            
            return [
                'pendapatan' => $labaRugi['revenue']['total'],
                'laba_bersih' => $labaRugi['net_profit'],
                'tiket_aktif' => ServiceTicket::whereNotIn('status', ['cancelled', 'picked_up'])->count(),
                'pesanan_hari_ini' => Order::whereDate('created_at', today())->count(),
                'rakitan_aktif' => PcAssembly::whereNotIn('status', ['completed', 'cancelled'])->count(),
                'penawaran_tertunda' => Quotation::where('status', 'pending')->count(),
            ];
        });

        // 2. Analisis Intelijen Bisnis (Cache 5 menit)
        $analisis = Cache::remember('dashboard_analysis', 300, function () use ($intelBisnis) {
            return $intelBisnis->getStockAnalysis(); // mengembalikan fast_moving, low_stock, dead_stock
        });

        return view('livewire.dashboard', [
            'statistik' => $statistik,
            'analisis' => $analisis,
        ]);
    }
}