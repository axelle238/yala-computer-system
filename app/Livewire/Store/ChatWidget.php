<?php

namespace App\Livewire\Store;

use App\Models\Order;
use App\Models\PesanObrolan;
use App\Models\Product;
use App\Models\SesiObrolan;
use App\Models\User;
use App\Notifications\NewChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;

class ChatWidget extends Component
{
    public $terbuka = false;

    public $sesi;

    public $pesanBaru = '';

    public $tokenTamu;

    public $modeBot = true;

    // Kamus data untuk koreksi typo & deteksi kategori
    private $kamusProduk = [
        'laptop', 'komputer', 'pc', 'mouse', 'keyboard', 'monitor', 'printer',
        'headset', 'ssd', 'ram', 'vga', 'gpu', 'cpu', 'processor', 'casing',
        'gaming', 'office', 'desain', 'sekolah', 'murah', 'mahal', 'promo',
        'asus', 'lenovo', 'hp', 'dell', 'acer', 'samsung', 'lg', 'logitech', // Brand
    ];

    // Data Ongkir Sederhana
    private $dataOngkir = [
        'jakarta' => 10000, 'bogor' => 15000, 'depok' => 15000,
        'tangerang' => 15000, 'bekasi' => 15000, 'bandung' => 20000,
        'surabaya' => 25000, 'semarang' => 30000, 'yogyakarta' => 30000,
        'medan' => 35000, 'denpasar' => 45000, 'makassar' => 45000,
    ];

    public function mount()
    {
        $this->tokenTamu = Session::get('token_tamu_chat');

        if (! $this->tokenTamu && ! Auth::check()) {
            $this->tokenTamu = Str::random(32);
            Session::put('token_tamu_chat', $this->tokenTamu);
        }

        $this->muatSesi();
    }

    public function muatSesi()
    {
        if (Auth::check()) {
            $this->sesi = SesiObrolan::where('id_pelanggan', Auth::id())->latest()->first();
        } else {
            $this->sesi = SesiObrolan::where('token_tamu', $this->tokenTamu)->latest()->first();
        }
    }

    public function togleChat()
    {
        $this->terbuka = ! $this->terbuka;
        if ($this->terbuka) {
            $this->tandaiDibaca();
        }
    }

    public function kirimPesan()
    {
        $this->validate(['pesanBaru' => 'required|string|min:1']);

        if (! $this->tokenTamu && ! Auth::check()) {
            $this->tokenTamu = Session::get('token_tamu_chat');
        }

        if (! $this->sesi) {
            $this->sesi = SesiObrolan::create([
                'id_pelanggan' => Auth::id(),
                'token_tamu' => Auth::check() ? null : $this->tokenTamu,
                'topik' => 'Obrolan Baru',
            ]);
        }

        $isAdminMode = Session::get('chat_mode_admin_'.$this->sesi->id, false);

        PesanObrolan::create([
            'id_sesi' => $this->sesi->id,
            'id_pengguna' => Auth::id(),
            'is_balasan_admin' => false,
            'isi' => $this->pesanBaru,
            'is_dibaca' => false,
        ]);

        $pesanTerkirim = $this->pesanBaru;
        $this->pesanBaru = '';

        // Deteksi perintah keluar dari mode admin
        if ($isAdminMode && (strtolower($pesanTerkirim) == 'selesai' || str_contains(strtolower($pesanTerkirim), 'kembali ke bot'))) {
            Session::forget('chat_mode_admin_'.$this->sesi->id);
            $isAdminMode = false;

            usleep(500000);
            $this->kirimBotResponse('Oke, YALA Smart Bot kembali aktif! 🤖 Ada yang bisa saya bantu lagi?');
            $this->dispatch('pesan-terkirim');

            return;
        }

        if ($this->modeBot && ! $isAdminMode) {
            usleep(rand(500000, 1000000));
            $this->prosesBot($pesanTerkirim);
        }

        $this->dispatch('pesan-terkirim');
    }

    public function akhiriChatAdmin()
    {
        if ($this->sesi) {
            Session::forget('chat_mode_admin_'.$this->sesi->id);
            $this->kirimBotResponse('Sesi dengan Admin telah diakhiri. YALA Smart Bot siap membantu kembali!');
            $this->dispatch('pesan-terkirim');
        }
    }

    /**
     * Logika AI Chat "YALA" Generasi 4 (Price & Spec Aware).
     */
    private function prosesBot($pesan)
    {
        $pesanOriginal = $pesan;
        $pesanLower = strtolower($pesan);
        $jawaban = '';

        // 1. Analisis Sentimen (Empati)
        if ($this->analisisSentimen($pesanLower)) {
            $jawaban = "Saya mendeteksi Anda mungkin sedang mengalami kendala serius. 😟 Mohon maaf atas ketidaknyamanannya. Ketik **'Admin'** untuk prioritas bantuan dari staf manusia kami.";
            $this->kirimBotResponse($jawaban);

            return;
        }

        // 2. Cek Ongkos Kirim (Smart Feature)
        if (preg_match('/ongkir.*ke\s+([a-z\s]+)/i', $pesanLower, $matches) || preg_match('/biaya.*kirim.*ke\s+([a-z\s]+)/i', $pesanLower, $matches)) {
            $kota = trim($matches[1]);
            $this->cekOngkir($kota);

            return;
        }

        // 3. Info Pembayaran (New Feature)
        if (str_contains($pesanLower, 'bayar') || str_contains($pesanLower, 'rekening') || str_contains($pesanLower, 'transfer')) {
            $jawaban = "💳 **Metode Pembayaran**\nKami menerima berbagai metode pembayaran:\n\n- Transfer Bank (BCA, Mandiri, BRI)\n- E-Wallet (GoPay, OVO, Dana)\n- Kartu Kredit/Debit\n\nSilakan pilih metode saat Checkout ya Kak!";
            $this->kirimBotResponse($jawaban);

            return;
        }

        // 4. Cek Status Toko
        if (str_contains($pesanLower, 'buka') || str_contains($pesanLower, 'tutup') || str_contains($pesanLower, 'jam')) {
            $this->cekStatusToko();

            return;
        }

        // 5. Deteksi Topik Khusus
        if (str_contains($pesanLower, 'lacak') || str_contains($pesanLower, 'resi')) {
            $jawaban = "🔍 **Lacak Pesanan**\nSilakan masukkan Nomor Pesanan (cth: #ORD-123) atau Nomor Resi Anda untuk pengecekan otomatis.";
        } elseif (str_contains($pesanLower, 'rakit') || str_contains($pesanLower, 'pc')) {
            $url = route('toko.rakit-pc');
            $jawaban = "🖥️ **Rakit PC Impian**\nCek kompatibilitas dan harga rakit PC secara instan di sini:\n👉 [Simulasi Rakit PC]($url)";
        } elseif (str_contains($pesanLower, 'admin') || str_contains($pesanLower, 'cs') || str_contains($pesanLower, 'orang')) {
            $jawaban = "Baik, saya sambungkan ke **Customer Service** kami. Mohon tunggu sebentar... ⏳\n(Ketik 'Selesai' untuk kembali ke mode Bot)";
            Session::put('chat_mode_admin_'.$this->sesi->id, true);
            $this->notifikasiAdmin($pesanOriginal);
        } elseif (preg_match('/\d{4,}/', $pesan, $matches)) {
            $this->cekStatusOrder($matches[0]);

            return;
        } elseif (preg_match('/\b(halo|hai|pagi|siang|sore|malam)\b/', $pesanLower)) {
            $jam = date('H');
            $sapaan = $jam < 12 ? 'Pagi' : ($jam < 15 ? 'Siang' : ($jam < 18 ? 'Sore' : 'Malam'));
            $user = Auth::user();
            $nama = $user ? explode(' ', $user->name)[0] : 'Kak';
            $jawaban = "Halo, Selamat {$sapaan} {$nama}! 👋\nSaya **YALA AI**. Saya makin pintar lho! Coba tanya:\n- \"Laptop Gaming dibawah 10 juta\"\n- \"Mouse Logitech murah\"\n- \"RAM 8GB\"";
        }

        // 6. Pencarian Produk Cerdas V2 (Deep Search dengan Filter Harga & Spek)
        if (empty($jawaban)) {
            $jawaban = $this->cariProdukAdvanced($pesanLower);
        }

        // 7. Fallback
        if (empty($jawaban)) {
            $jawaban = $this->jawabanVariatif('bingung');
        }

        $this->kirimBotResponse($jawaban);
    }

    private function cekOngkir($kotaInput)
    {
        $kotaDitemukan = null;
        foreach ($this->dataOngkir as $kotaDb => $harga) {
            if (str_contains($kotaInput, $kotaDb) || levenshtein($kotaInput, $kotaDb) <= 2) {
                $kotaDitemukan = $kotaDb;
                break;
            }
        }

        if ($kotaDitemukan) {
            $harga = $this->dataOngkir[$kotaDitemukan];
            $msg = '🚚 **Estimasi Ongkir ke '.ucwords($kotaDitemukan)."**\n\nRp ".number_format($harga, 0, ',', '.')." / kg (JNE Reguler)\n_Harga dapat berubah tergantung berat total pesanan._";
        } else {
            $msg = "Maaf, data ongkir untuk **{$kotaInput}** belum tersedia di database bot saya. Silakan cek detail pastinya saat Checkout ya Kak.";
        }

        $this->kirimBotResponse($msg);
    }

    private function cekStatusToko()
    {
        $jamSekarang = (int) date('H');
        $hariSekarang = date('N'); // 1 (Senin) - 7 (Minggu)
        $buka = false;
        $pesanStatus = '';

        if ($hariSekarang <= 6) { // Senin-Sabtu
            if ($jamSekarang >= 9 && $jamSekarang < 20) {
                $buka = true;
                $sisaJam = 20 - $jamSekarang;
                $pesanStatus = "✅ **Toko Sedang BUKA**\nKami tutup {$sisaJam} jam lagi (Pukul 20:00 WIB).";
            } else {
                $pesanStatus = "⛔ **Toko Sedang TUTUP**\nKami buka kembali besok pukul 09:00 WIB.";
            }
        } else { // Minggu
            if ($jamSekarang >= 10 && $jamSekarang < 18) {
                $buka = true;
                $pesanStatus = "✅ **Toko Sedang BUKA** (Minggu)\nKami tutup pukul 18:00 WIB.";
            } else {
                $pesanStatus = "⛔ **Toko Sedang TUTUP**\nKami buka kembali Senin pukul 09:00 WIB.";
            }
        }

        $this->kirimBotResponse("{$pesanStatus}\n\n📍 **Alamat:** Jl. Teknologi No. 88, Jakarta Selatan.\n💡 Order via website tetap buka 24 Jam!");
    }

    private function cekStatusOrder($orderId)
    {
        $cleanId = str_replace('#', '', $orderId);
        $order = Order::where('id', $cleanId)->orWhere('order_number', 'like', "%{$cleanId}%")->first();

        if ($order) {
            $msg = "📦 **Info Pesanan #{$order->order_number}**\nStatus: **".strtoupper($order->status)."**\nTotal: Rp ".number_format($order->total_amount, 0, ',', '.');
            if ($order->status == 'shipped') {
                $msg .= "\n\n🚚 Resi: `{$order->shipping_tracking_number}`";
            }
            $this->kirimBotResponse($msg);
        } else {
            $this->kirimBotResponse("Saya mencari pesanan **#{$cleanId}** tapi tidak ditemukan. Mohon pastikan nomornya benar ya Kak.");
        }
    }

    /**
     * Pencarian Produk Cerdas V2: Mendukung filter harga, spek, dan sorting.
     */
    private function cariProdukAdvanced($input)
    {
        // 1. Ekstraksi Filter Harga
        $maxPrice = null;
        $minPrice = null;

        // Regex: "dibawah 5 juta", "kurang dari 5jt", "max 500rb"
        if (preg_match('/(dibawah|bawah|kurang dari|max|maksimal)\s*(\d+(\.\d+)?)\s*(jt|juta|ribu|rb)/i', $input, $matches)) {
            $angka = (float) $matches[2];
            $satuan = strtolower($matches[4]);
            if (in_array($satuan, ['jt', 'juta'])) {
                $maxPrice = $angka * 1000000;
            } elseif (in_array($satuan, ['ribu', 'rb'])) {
                $maxPrice = $angka * 1000;
            }
        }

        // Regex: "diatas 5 juta", "lebih dari 5jt", "min 500rb"
        if (preg_match('/(diatas|atas|lebih dari|min|minimal)\s*(\d+(\.\d+)?)\s*(jt|juta|ribu|rb)/i', $input, $matches)) {
            $angka = (float) $matches[2];
            $satuan = strtolower($matches[4]);
            if (in_array($satuan, ['jt', 'juta'])) {
                $minPrice = $angka * 1000000;
            } elseif (in_array($satuan, ['ribu', 'rb'])) {
                $minPrice = $angka * 1000;
            }
        }

        // 2. Normalisasi Kata Kunci & Deteksi Spek
        $rawWords = explode(' ', $input);
        $cleanWords = [];
        $specs = []; // Menyimpan spek teknis (GB, SSD, RTX)

        $stopWords = ['apakah', 'ada', 'jual', 'stok', 'harga', 'berapa', 'yang', 'mau', 'beli', 'cari', 'tolong', 'rekomendasi', 'buat', 'untuk', 'dengan', 'spek', 'spesifikasi', 'dibawah', 'diatas', 'juta', 'ribu', 'jt', 'rb', 'murah', 'mahal'];

        foreach ($rawWords as $word) {
            $wordLower = strtolower($word);

            // Lewati angka murni (biasanya harga) kecuali ada unit spek
            if (is_numeric($word)) {
                continue;
            }

            // Deteksi Spek (GB, TB, Hz, Core, Ryzen)
            if (preg_match('/[0-9]+(gb|tb|hz)/i', $word) || in_array($wordLower, ['ssd', 'hdd', 'ips', 'oled', 'rtx', 'gtx', 'intel', 'amd', 'ryzen', 'core'])) {
                $specs[] = $word;

                continue;
            }

            if (in_array($wordLower, $stopWords) || strlen($word) < 2) {
                continue;
            }

            // Koreksi Typo
            $found = $wordLower;
            foreach ($this->kamusProduk as $term) {
                if (levenshtein($wordLower, $term) <= 1) {
                    $found = $term;
                    break;
                }
            }
            $cleanWords[] = $found;
        }

        if (empty($cleanWords) && empty($specs) && is_null($maxPrice) && is_null($minPrice)) {
            return null;
        }

        // 3. Query Database
        $query = Product::query()->where('is_active', true);

        // Filter Kata Kunci Nama/Kategori
        if (! empty($cleanWords)) {
            $query->where(function ($q) use ($cleanWords) {
                foreach ($cleanWords as $word) {
                    $q->orWhere('name', 'like', "%{$word}%")
                        ->orWhereHas('kategori', fn ($k) => $k->where('name', 'like', "%{$word}%"));
                }
            });
        }

        // Filter Spesifikasi (Cari di deskripsi atau nama)
        if (! empty($specs)) {
            foreach ($specs as $spec) {
                $query->where(function ($q) use ($spec) {
                    $q->where('name', 'like', "%{$spec}%")
                        ->orWhere('description', 'like', "%{$spec}%");
                });
            }
        }

        // Filter Harga
        if ($maxPrice) {
            $query->where('sell_price', '<=', $maxPrice);
        }
        if ($minPrice) {
            $query->where('sell_price', '>=', $minPrice);
        }

        // Sorting
        if (str_contains($input, 'termurah') || str_contains($input, 'paling murah')) {
            $query->orderBy('sell_price', 'asc');
        } elseif (str_contains($input, 'termahal') || str_contains($input, 'paling mahal') || str_contains($input, 'sultan')) {
            $query->orderBy('sell_price', 'desc');
        } elseif (str_contains($input, 'terbaru')) {
            $query->latest();
        }

        $hasil = $query->take(3)->get();

        if ($hasil->count() > 0) {
            $response = "Saya menemukan produk yang cocok:\n";

            foreach ($hasil as $p) {
                $stokInfo = $p->stock_quantity > 0 ? '✅ Ready' : '❌ Habis';
                $response .= "\n🔹 **[{$p->name}](".route('toko.produk.detail', $p->id).")**\n   Rp ".number_format($p->sell_price, 0, ',', '.')." | {$stokInfo}";
            }

            if ($maxPrice) {
                $response .= "\n\n_Difilter: Budget maksimal Rp ".number_format($maxPrice, 0, ',', '.').'_';
            }

            return $response;
        }

        // Jika tidak ada hasil dengan filter harga, coba cari tanpa filter harga (Saran Alternatif)
        if ($maxPrice || $minPrice) {
            $query->withoutGlobalScopes()->where('is_active', true); // Reset scope simplistik
            // Re-apply basic keywords only
            if (! empty($cleanWords)) {
                $query->where(function ($q) use ($cleanWords) {
                    foreach ($cleanWords as $word) {
                        $q->orWhere('name', 'like', "%{$word}%");
                    }
                });
                $altHasil = $query->take(2)->get();
                if ($altHasil->count() > 0) {
                    $response = "Waduh, produk dengan budget segitu belum ada Kak. 😅\n\nTapi saya punya rekomendasi serupa (harga beda dikit):\n";
                    foreach ($altHasil as $p) {
                        $response .= "\n🔸 **{$p->name}**\n   Rp ".number_format($p->sell_price, 0, ',', '.');
                    }

                    return $response;
                }
            }
        }

        return null;
    }

    private function analisisSentimen($text)
    {
        $kataNegatif = ['kecewa', 'lama', 'lambat', 'penipu', 'rusak', 'batal', 'jelek', 'bodoh', 'anjing', 'babi', 'parah', 'gimana sih'];
        foreach ($kataNegatif as $bad) {
            if (str_contains($text, $bad)) {
                return true;
            }
        }

        return false;
    }

    private function jawabanVariatif($tipe)
    {
        $opsi = [
            "Maaf, saya masih belajar memahami itu. 🤔 Coba gunakan kata kunci produk langsung (misal: 'Monitor LG' atau 'Laptop 5 juta').",
            'Saya kurang yakin maksudnya. Apakah Anda ingin mencari produk, cek resi, atau tanya ongkir?',
            'Waduh, Yala belum diajari tentang hal itu. Tapi Yala bisa bantu carikan barang impianmu! Coba ketik nama barangnya.',
            "Hmm.. Pertanyaan sulit! 😅 Coba ketik **'Admin'** agar dibantu staf ahli kami.",
        ];

        return $opsi[array_rand($opsi)];
    }

    private function kirimBotResponse($isi)
    {
        PesanObrolan::create([
            'id_sesi' => $this->sesi->id,
            'id_pengguna' => null,
            'is_balasan_admin' => true,
            'isi' => $isi,
            'is_dibaca' => true,
        ]);
    }

    private function notifikasiAdmin($pesan)
    {
        $admins = User::where('role', 'admin')->get();
        if ($admins->count() > 0) {
            Notification::send($admins, new NewChatMessage($pesan, Auth::user()->name ?? 'Tamu'));
        }
    }

    public function tandaiDibaca()
    {
        if ($this->sesi) {
            $this->sesi->pesan()
                ->where('is_balasan_admin', true)
                ->where('is_dibaca', false)
                ->update(['is_dibaca' => true]);
        }
    }

    public function render()
    {
        return view('livewire.store.chat-widget', [
            'daftarPesan' => $this->sesi ? $this->sesi->pesan()->orderBy('created_at', 'desc')->orderBy('id', 'desc')->take(50)->get()->reverse() : collect([]),
            'isAdminMode' => $this->sesi ? Session::get('chat_mode_admin_'.$this->sesi->id, false) : false,
        ]);
    }
}
