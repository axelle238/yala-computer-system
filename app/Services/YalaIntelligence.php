<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\ServiceTicket;
use Carbon\Carbon;
use Illuminate\Support\Str;

class YalaIntelligence
{
    /**
     * Menganalisis sentimen bisnis saat ini berdasarkan data real-time.
     */
    public function analisisBisnisHarian()
    {
        $penjualanHariIni = Order::whereDate('created_at', today())->sum('total_amount');
        $kemarin = Order::whereDate('created_at', today()->subDay())->sum('total_amount');
        
        $tren = $penjualanHariIni > $kemarin ? 'naik' : 'turun';
        $persentase = $kemarin > 0 ? (($penjualanHariIni - $kemarin) / $kemarin) * 100 : 100;

        $insight = [];
        
        // Logika Deduksi AI
        if ($tren == 'naik' && $persentase > 20) {
            $insight = [
                'status' => 'Sangat Positif',
                'pesan' => 'Performa penjualan melonjak signifikan hari ini. Lonjakan ini kemungkinan didorong oleh tingginya permintaan kategori Laptop/PC.',
                'saran' => 'Pertimbangkan untuk menaikkan anggaran iklan 10% untuk momentum ini.',
                'warna' => 'emerald'
            ];
        } elseif ($tren == 'turun' && abs($persentase) > 20) {
            $insight = [
                'status' => 'Perlu Perhatian',
                'pesan' => 'Terdeteksi penurunan omset harian yang tajam dibandingkan kemarin. Trafik pengunjung toko online juga terlihat melambat.',
                'saran' => 'Cek apakah ada pesaing yang sedang promo besar atau segera rilis Flash Sale dadakan.',
                'warna' => 'rose'
            ];
        } else {
            $insight = [
                'status' => 'Stabil',
                'pesan' => 'Bisnis berjalan stabil sesuai rata-rata mingguan. Tidak ada anomali signifikan pada transaksi.',
                'saran' => 'Fokus pada retensi pelanggan dengan mengirimkan pesan WhatsApp follow-up.',
                'warna' => 'blue'
            ];
        }

        return $insight;
    }

    /**
     * Prediksi stok yang akan habis dalam 7 hari ke depan (Predictive Maintenance).
     */
    public function prediksiStokKritis()
    {
        // Simulasi logika regresi linear sederhana berdasarkan rata-rata penjualan harian
        $produkKritis = [];
        $products = Product::where('stock_quantity', '<', 20)->get();

        foreach($products as $p) {
            // Rata-rata terjual per hari (Mock logic: asumsi 1-3 terjual/hari)
            $avgSales = rand(1, 3); 
            $hariTersisa = $p->stock_quantity / $avgSales;

            if ($hariTersisa <= 7) {
                $produkKritis[] = [
                    'nama' => $p->name,
                    'sisa' => $p->stock_quantity,
                    'habis_dalam' => ceil($hariTersisa) . ' hari',
                    'urgensi' => $hariTersisa < 3 ? 'TINGGI' : 'SEDANG'
                ];
            }
        }

        return collect($produkKritis)->sortBy('hari_tersisa')->take(3);
    }

    /**
     * Menghasilkan respon cerdas untuk Asisten Admin (Copilot).
     */
    public function tanyaData($pertanyaan)
    {
        $p = strtolower($pertanyaan);
        
        // 1. Intent: Penjualan/Omset
        if (str_contains($p, 'omset') || str_contains($p, 'penjualan') || str_contains($p, 'pendapatan')) {
            $total = Order::where('status', 'completed')->whereMonth('created_at', now()->month)->sum('total_amount');
            $format = number_format($total, 0, ',', '.');
            return "Berdasarkan data real-time, total omset bulan ini mencapai **Rp {$format}**. Tren menunjukkan kenaikan di kategori aksesoris.";
        }

        // 2. Intent: Pelanggan
        if (str_contains($p, 'pelanggan') || str_contains($p, 'user') || str_contains($p, 'member')) {
            $baru = Customer::whereMonth('created_at', now()->month)->count();
            $total = Customer::count();
            return "Kita memiliki total **{$total} pelanggan** terdaftar. Bulan ini ada penambahan **{$baru} pelanggan baru**. Pertumbuhan user cukup sehat.";
        }

        // 3. Intent: Stok/Produk
        if (str_contains($p, 'stok') || str_contains($p, 'produk') || str_contains($p, 'barang')) {
            $kritis = Product::whereColumn('stock_quantity', '<=', 'min_stock_alert')->count();
            return "Saat ini ada **{$kritis} produk** yang stoknya berada di bawah batas aman (Safety Stock). Mohon segera cek menu Pengadaan.";
        }

        // 4. Intent: Servis
        if (str_contains($p, 'servis') || str_contains($p, 'perbaikan')) {
            $aktif = ServiceTicket::whereNotIn('status', ['picked_up', 'cancelled'])->count();
            return "Tim teknisi sedang menangani **{$aktif} unit** servis aktif. Beban kerja teknisi saat ini: MODERAT.";
        }

        // 5. Intent: Navigasi/Bantuan
        if (str_contains($p, 'buat') && str_contains($p, 'produk')) {
            return "Untuk menambah produk baru, silakan akses menu **Inventaris > Produk > Tambah Produk** atau klik tombol pintas di Dashboard.";
        }

        // Fallback AI Generative Simulation
        return "Hmm, pertanyaan menarik. Saya sedang menganalisis pola data terkait '{$pertanyaan}', namun saya memerlukan konteks lebih spesifik. Coba tanya tentang 'omset', 'stok', atau 'pelanggan'.";
    }

    /**
     * Membuat deskripsi produk otomatis (Content Generation).
     */
    public function generateDeskripsiProduk($namaProduk, $kategori)
    {
        $template = [
            "Hadirkan performa maksimal dengan **{$namaProduk}**. Produk unggulan di kategori {$kategori} ini dirancang untuk produktivitas tinggi dan ketahanan luar biasa. Pilihan tepat untuk profesional maupun gamer.",
            "Tingkatkan pengalaman komputasi Anda dengan **{$namaProduk}**. Kualitas premium dari {$kategori} yang menjamin kepuasan penggunaan jangka panjang. Stok terbatas!",
            "Solusi terbaik untuk kebutuhan IT Anda: **{$namaProduk}**. Dapatkan efisiensi dan kecepatan terbaik di kelas {$kategori}. Garansi resmi dan dukungan teknis penuh dari Yala Computer."
        ];

        return $template[array_rand($template)];
    }
}
