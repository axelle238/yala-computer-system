<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\PcAssembly;
use App\Models\PesanPelanggan;
use App\Models\Quotation;
use App\Models\ServiceTicket; // Model baru
use App\Services\BusinessIntelligence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Dashboard Utama - Yala Computer')]
class Dashboard extends Component
{
    public function render()
    {
        $intelBisnis = new BusinessIntelligence;
        $bulan = now()->month;
        $tahun = now()->year;
        $pengguna = Auth::user();

        // 1. Statistik Inti (Cache 60 detik untuk performa)
        $statistik = Cache::remember('dashboard_statistik_inti_'.$pengguna->id, 60, function () use ($bulan, $tahun, $intelBisnis, $pengguna) {
            $labaRugi = $intelBisnis->getProfitLoss($bulan, $tahun);

            $data = [
                'pendapatan' => $labaRugi['revenue']['total'],
                'laba_bersih' => $labaRugi['net_profit'],
                'tiket_aktif' => ServiceTicket::whereNotIn('status', ['picked_up', 'cancelled'])->count(), // Status diperbarui
                'pesanan_hari_ini' => Order::whereDate('created_at', today())->count(),
                'rakitan_aktif' => PcAssembly::whereNotIn('status', ['completed', 'cancelled'])->count(),
                'penawaran_tertunda' => Quotation::where('status', 'pending')->count(),
                'pesan_baru' => PesanPelanggan::where('status', PesanPelanggan::STATUS_BARU)->count(), // Fitur baru
                'log_aktivitas' => ActivityLog::with('user')->latest()->take(10)->get(),
            ];

            // Penyesuaian berdasarkan Peran
            if ($pengguna->peran && $pengguna->peran->nama === 'Teknisi') {
                $data['tiket_milik_saya'] = ServiceTicket::where('technician_id', $pengguna->id)
                    ->whereNotIn('status', ['picked_up', 'cancelled'])
                    ->count();
                $data['servis_siap_ambil'] = ServiceTicket::where('technician_id', $pengguna->id)
                    ->where('status', 'ready')
                    ->count();
            }

            // Data Grafik Penjualan 7 Hari Terakhir
            $data['grafik_penjualan'] = Order::selectRaw('DATE(created_at) as tanggal, SUM(total_amount) as total')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->get()
                ->map(fn($item) => [
                    'tanggal' => \Carbon\Carbon::parse($item->tanggal)->format('d M'),
                    'total' => (int) $item->total
                ]);

            return $data;
        });

        // 2. Analisis Intelijen Bisnis (Cache 5 menit)
        $analisis = Cache::remember('dashboard_analisis', 300, function () use ($intelBisnis) {
            return $intelBisnis->getStockAnalysis(); // mengembalikan fast_moving, low_stock, dead_stock
        });

        return view('livewire.dashboard', [
            'statistik' => $statistik,
            'analisis' => $analisis,
            'pengguna' => $pengguna,
        ]);
    }
}
