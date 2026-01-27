<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\PcAssembly;
use App\Models\PesanPelanggan;
use App\Models\Quotation;
use App\Models\ServiceTicket;
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
    /**
     * Render komponen Dashboard Admin.
     */
    public function render()
    {
        $intelBisnis = new BusinessIntelligence;
        $bulan = now()->month;
        $tahun = now()->year;
        $pengguna = Auth::user();

        // 1. Statistik Inti (Cache 60 detik)
        $statistik = Cache::remember('dashboard_statistik_inti_'.$pengguna->id, 60, function () use ($bulan, $tahun, $intelBisnis, $pengguna) {
            $labaRugi = $intelBisnis->ambilLabaRugi($bulan, $tahun);

            $data = [
                'pendapatan' => $labaRugi['pendapatan']['total'],
                'laba_bersih' => $labaRugi['laba_bersih'],
                'tiket_aktif' => ServiceTicket::whereNotIn('status', ['picked_up', 'cancelled'])->count(),
                'pesanan_hari_ini' => Order::whereDate('created_at', today())->count(),
                'rakitan_aktif' => PcAssembly::whereNotIn('status', ['completed', 'cancelled'])->count(),
                'penawaran_tertunda' => Quotation::where('status', 'pending')->count(),
                'pesan_baru' => PesanPelanggan::where('status', PesanPelanggan::STATUS_BARU)->count(),
                'log_aktivitas' => ActivityLog::with('user')->latest()->take(10)->get(),
            ];

            // Penyesuaian berdasarkan Peran Teknisi
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

        // 2. Analisis Inventaris (Cache 5 menit)
        $analisis = Cache::remember('dashboard_analisis', 300, function () use ($intelBisnis) {
            $hasil = $intelBisnis->ambilAnalisisStok();
            
            // Map kembali kunci untuk view agar tetap sinkron
            return [
                'fast_moving' => $hasil['laku_pesat'],
                'low_stock' => $hasil['stok_menipis'],
                'dead_stock' => $hasil['stok_mati'],
            ];
        });

        return view('livewire.dashboard', [
            'statistik' => $statistik,
            'analisis' => $analisis,
            'pengguna' => $pengguna,
        ]);
    }
}