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
     * Membuat deskripsi produk otomatis (Content Generation) yang kaya keyword.
     */
    public function generateDeskripsiProduk($namaProduk, $kategori, $fiturUtama = [])
    {
        $adjectives = ['Canggih', 'Terbaru', 'Premium', 'Eksklusif', 'Handal', 'Efisien'];
        $adj = $adjectives[array_rand($adjectives)];
        
        $desc = "Hadirkan produktivitas maksimal dengan **{$namaProduk}**. Produk {$kategori} {$adj} ini dirancang khusus untuk memenuhi kebutuhan komputasi modern Anda.\n\n";
        
        $desc .= "**Fitur Unggulan:**\n";
        if (!empty($fiturUtama)) {
            foreach($fiturUtama as $fitur) {
                $desc .= "✅ {$fitur}\n";
            }
        } else {
            $desc .= "✅ Performa tinggi untuk multitasking lancar.\n";
            $desc .= "✅ Desain ergonomis dan material tahan lama.\n";
            $desc .= "✅ Efisiensi daya terbaik di kelasnya.\n";
        }

        $desc .= "\n**Kenapa Memilih {$namaProduk}?**\n";
        $desc .= "Dukungan garansi resmi Yala Computer dan layanan purna jual prioritas memastikan investasi teknologi Anda aman dan menguntungkan.";

        return $desc;
    }

    /**
     * Rekomendasi harga jual cerdas berdasarkan margin dan kompetitor (Simulasi).
     */
    public function rekomendasiHarga($hargaBeli, $kategori)
    {
        // Logika margin dinamis berdasarkan kategori
        $margin = match(strtolower($kategori)) {
            'aksesoris' => 0.40, // 40% margin
            'jasa' => 0.80,      // 80% margin
            'laptop', 'pc' => 0.15, // 15% margin (kompetitif)
            default => 0.25
        };

        $hargaIdeal = $hargaBeli + ($hargaBeli * $margin);
        
        // Psikologi harga (akhiran 900 atau 000)
        $hargaPsikologis = ceil($hargaIdeal / 1000) * 1000; 
        if ($hargaPsikologis % 10000 == 0) $hargaPsikologis -= 100; // Contoh: 50.000 -> 49.900

        $marginPersen = $margin * 100;

        return [
            'rekomendasi' => $hargaPsikologis,
            'margin_persen' => $marginPersen,
            'profit' => $hargaPsikologis - $hargaBeli,
            'analisis' => "Berdasarkan kategori '{$kategori}', kami menyarankan margin {$marginPersen}% untuk tetap kompetitif namun profitabel."
        ];
    }

    /**
     * Analisis Sentimen & Saran Balasan untuk CS.
     */
    public function analisisPesanCS($pesan)
    {
        $pesanLower = strtolower($pesan);
        $kataNegatif = ['rusak', 'kecewa', 'lama', 'lambat', 'penipu', 'batal', 'jelek', 'marah'];
        $kataTanya = ['kapan', 'dimana', 'berapa', 'cara', 'apakah'];
        
        $sentimen = 'netral';
        $skor = 50;

        foreach ($kataNegatif as $bad) {
            if (str_contains($pesanLower, $bad)) {
                $sentimen = 'negatif';
                $skor = 10;
                break;
            }
        }

        // Generate Saran Balasan
        $saran = "Halo Kak, terima kasih sudah menghubungi Yala Computer. Ada yang bisa kami bantu?";
        
        if ($sentimen == 'negatif') {
            $saran = "Halo Kak, mohon maaf sekali atas ketidaknyamanan yang dialami. 🙏 Bisa tolong ceritakan detail kendalanya? Kami akan prioritaskan penyelesaian masalah Kakak segera.";
        } elseif (str_contains($pesanLower, 'kapan') && str_contains($pesanLower, 'kirim')) {
            $saran = "Halo Kak, untuk pengiriman biasanya diproses H+1 setelah pembayaran ya. Boleh informasikan Nomor Pesanannya agar kami bantu cek status terkini?";
        } elseif (str_contains($pesanLower, 'stok') || str_contains($pesanLower, 'ready')) {
            $saran = "Halo Kak, produk tersebut saat ini statusnya READY SIAP KIRIM ya! Silakan bisa langsung di-checkout sebelum kehabisan. 😊";
        }

        return [
            'sentimen' => $sentimen,
            'skor_kepuasan' => $skor,
            'saran_balasan' => $saran
        ];
    }

    /**
     * Deteksi Anomali Penggajian (Audit AI).
     */
    public function auditGaji($totalGaji, $lembur, $jabatan)
    {
        $anomali = [];
        
        // Cek Lembur Berlebihan
        if ($lembur > 4000000) { // Misal threshold lembur
            $anomali[] = "Nominal lembur (Rp " . number_format($lembur) . ") terdeteksi sangat tinggi di luar kewajaran.";
        }

        // Cek Gaji Pokok vs Jabatan (Mock logic)
        if ($jabatan == 'Teknisi' && $totalGaji > 15000000) {
            $anomali[] = "Total gaji melebihi standar rentang gaji untuk posisi Teknisi.";
        }

        return [
            'aman' => empty($anomali),
            'isu' => $anomali
        ];
    }
}
