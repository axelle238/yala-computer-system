<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\PcAssembly;
use App\Models\PesanPelanggan;
use App\Models\Quotation;
use App\Models\ServiceTicket;
use App\Models\Product;
use App\Services\BusinessIntelligence;
use App\Services\YalaIntelligence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
    public function render(YalaIntelligence $ai)
    {
        $pengguna = Auth::user();
        
        // --- 0. AI INSIGHTS (Yala Brain) ---
        $aiInsight = $ai->analisisBisnisHarian();
        $prediksiStok = $ai->prediksiStokKritis();
        
        // --- 1. RINGKASAN EKSEKUTIF (Semua Departemen) ---
        
        // Keuangan Hari Ini
        $penjualanHariIni = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');
            
        $servisHariIni = ServiceTicket::whereDate('updated_at', today())
            ->where('status', 'picked_up')
            ->count(); // Idealnya sum biaya servis, tapi simplified untuk count dulu

        // Aktivitas Operasional
        $pesananPending = Order::where('status', 'pending')->count();
        $servisAktif = ServiceTicket::whereNotIn('status', ['picked_up', 'cancelled'])->count();
        $rakitanProses = PcAssembly::where('status', 'building')->count();
        
        // Isu & Peringatan
        $stokMenipis = Product::whereColumn('stock_quantity', '<=', 'min_stock_alert')->count();
        $chatBelumDibaca = PesanPelanggan::where('status', 'unread')->count();

        // --- 2. GRAFIK TREN 7 HARI ---
        $grafikPenjualan = Order::selectRaw('DATE(created_at) as tanggal, SUM(total_amount) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->where('status', 'completed')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->map(fn ($item) => [
                'tanggal' => \Carbon\Carbon::parse($item->tanggal)->format('d M'),
                'total' => (int) $item->total,
            ]);

        // --- 3. AKTIVITAS TERBARU (Feed) ---
        $aktivitasTerbaru = ActivityLog::with('user')
            ->latest()
            ->take(6)
            ->get();

        // --- 4. TOP PERFORMERS ---
        $produkTerlaris = Product::withCount(['orderItems as terjual' => function($query) {
                $query->whereHas('order', fn($q) => $q->where('status', 'completed'));
            }])
            ->orderByDesc('terjual')
            ->take(5)
            ->get();

        return view('livewire.dashboard', [
            'aiInsight' => $aiInsight,
            'prediksiStok' => $prediksiStok,
            'ringkasan' => [
                'omset_hari_ini' => $penjualanHariIni,
                'pesanan_pending' => $pesananPending,
                'servis_aktif' => $servisAktif,
                'stok_kritis' => $stokMenipis,
                'rakitan_proses' => $rakitanProses,
                'chat_unread' => $chatBelumDibaca
            ],
            'grafik' => $grafikPenjualan,
            'aktivitas' => $aktivitasTerbaru,
            'top_produk' => $produkTerlaris,
            'pengguna' => $pengguna,
        ]);
    }
}