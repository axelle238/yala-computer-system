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

    // Kamus Normalisasi (Singkatan -> Baku)
    private $kamusSlang = [
        'yg' => 'yang', 'gk' => 'tidak', 'gak' => 'tidak', 'nggak' => 'tidak',
        'brp' => 'berapa', 'harganya' => 'harga', 'hrg' => 'harga',
        'bgs' => 'bagus', 'mrh' => 'murah', 'mhl' => 'mahal',
        'ad' => 'ada', 'ready' => 'ada', 'redy' => 'ada',
        'min' => 'admin', 'gan' => 'kak', 'sis' => 'kak', 'bro' => 'kak',
        'bisa' => 'bisa', 'bs' => 'bisa',
        'kirim' => 'kirim', 'krm' => 'kirim',
        'ongkir' => 'ongkos kirim', 'ongkos' => 'ongkos kirim',
        'tp' => 'tapi', 'trus' => 'lalu',
        'blm' => 'belum', 'udh' => 'sudah', 'sdh' => 'sudah',
        'dmn' => 'dimana', 'dmn' => 'dimana',
        'sy' => 'saya', 'aq' => 'saya', 'aku' => 'saya',
        'mw' => 'mau', 'pengen' => 'mau',
        'byr' => 'bayar', 'trf' => 'transfer',
        'spek' => 'spesifikasi', 'spec' => 'spesifikasi',
        'mobo' => 'motherboard', 'proc' => 'processor', 'procie' => 'processor',
        'lepi' => 'laptop', 'leptop' => 'laptop',
        'garansi' => 'garansi', 'gransi' => 'garansi', 'warr' => 'garansi',
    ];

    // Kamus data untuk koreksi typo & deteksi kategori
    private $kamusProduk = [
        'laptop', 'komputer', 'pc', 'mouse', 'keyboard', 'monitor', 'printer',
        'headset', 'ssd', 'ram', 'vga', 'gpu', 'cpu', 'processor', 'casing',
        'gaming', 'office', 'desain', 'sekolah', 'murah', 'mahal', 'promo',
        'asus', 'lenovo', 'hp', 'dell', 'acer', 'samsung', 'lg', 'logitech',
        'motherboard', 'intel', 'amd', 'ryzen', 'nvidia', 'radeon',
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
     * Logika AI Chat "YALA" Generasi 5 (NLP & Slang Support).
     */
    private function prosesBot($pesan)
    {
        $pesanOriginal = $pesan;

        // 1. Normalisasi Bahasa (Slang -> Baku)
        $pesanNormal = $this->normalisasiPesan(strtolower($pesan));

        // Deteksi Gaya Bahasa User (Formal vs Santai)
        $isSantai = str_contains(strtolower($pesan), 'gan') || str_contains(strtolower($pesan), 'bro') || str_contains(strtolower($pesan), 'min');
        $sapaanUser = $isSantai ? 'Gan' : 'Kak';

        $jawaban = '';

        // 2. Analisis Sentimen (Empati)
        if ($this->analisisSentimen($pesanNormal)) {
            $jawaban = "Waduh, maaf banget ya {$sapaanUser} kalau ada yang bikin gak nyaman. 😟 Boleh ketik **'Admin'** biar langsung dibantu sama tim support kami?";
            $this->kirimBotResponse($jawaban);

            return;
        }

        // 3. Cek Ongkos Kirim
        if (preg_match('/ongkir.*ke\s+([a-z\s]+)/i', $pesanNormal, $matches) || preg_match('/biaya.*kirim.*ke\s+([a-z\s]+)/i', $pesanNormal, $matches)) {
            $kota = trim($matches[1]);
            $this->cekOngkir($kota, $sapaanUser);

            return;
        }

        // 4. Info Pembayaran
        if (str_contains($pesanNormal, 'bayar') || str_contains($pesanNormal, 'rekening') || str_contains($pesanNormal, 'transfer')) {
            $jawaban = "💳 **Cara Bayar**\nBisa transfer (BCA, Mandiri), E-Wallet (GoPay, OVO), atau Kartu Kredit {$sapaanUser}. Pilih aja pas Checkout nanti ya!";
            $this->kirimBotResponse($jawaban);

            return;
        }

        // 5. Cek Status Toko
        if (str_contains($pesanNormal, 'buka') || str_contains($pesanNormal, 'tutup') || str_contains($pesanNormal, 'jam')) {
            $this->cekStatusToko($sapaanUser);

            return;
        }

        // 6. Deteksi Topik Khusus
        if (str_contains($pesanNormal, 'lacak') || str_contains($pesanNormal, 'resi')) {
            $jawaban = "🔍 **Lacak Paket**\nKetik Nomor Pesanan (misal: #ORD-123) atau Resi-nya disini ya {$sapaanUser}.";
        } elseif (str_contains($pesanNormal, 'rakit') || str_contains($pesanNormal, 'pc')) {
            $jawaban = "🖥️ **Rakit PC**\nCek simulasi rakit PC disini {$sapaanUser}, lengkap sama harganya:\n👉 [Simulasi Rakit PC](".route('toko.rakit-pc').')';
        } elseif (str_contains($pesanNormal, 'admin') || str_contains($pesanNormal, 'cs') || str_contains($pesanNormal, 'orang')) {
            $jawaban = "Oke siap, saya panggilkan Admin CS sebentar ya {$sapaanUser}... ⏳\n(Ketik 'Selesai' kalau mau balik ke Bot)";
            Session::put('chat_mode_admin_'.$this->sesi->id, true);
            $this->notifikasiAdmin($pesanOriginal);
        } elseif (preg_match('/\d{4,}/', $pesanNormal, $matches)) {
            $this->cekStatusOrder($matches[0]);

            return;
        } elseif (preg_match('/\b(halo|hai|pagi|siang|sore|malam)\b/', $pesanNormal)) {
            $jam = date('H');
            $waktu = $jam < 12 ? 'Pagi' : ($jam < 15 ? 'Siang' : ($jam < 18 ? 'Sore' : 'Malam'));
            $jawaban = "Halo, Selamat {$waktu} {$sapaanUser}! 👋\nYALA siap bantu. Mau cari **Laptop Gaming**, **Cek Ongkir**, atau **Rakit PC**?";
        }

        // 7. Pencarian Produk Cerdas (Advanced Search)
        if (empty($jawaban)) {
            $jawaban = $this->cariProdukAdvanced($pesanNormal, $sapaanUser);
        }

        // 8. Fallback
        if (empty($jawaban)) {
            $jawaban = $this->jawabanVariatif($sapaanUser);
        }

        $this->kirimBotResponse($jawaban);
    }

    /**
     * Menormalkan kalimat gaul/singkatan menjadi bahasa baku yang dimengerti mesin.
     */
    private function normalisasiPesan($text)
    {
        $words = explode(' ', $text);
        $cleanWords = [];

        foreach ($words as $word) {
            // Hapus tanda baca
            $cleanWord = preg_replace('/[^a-z0-9]/', '', $word);

            // Cek di kamus slang
            if (isset($this->kamusSlang[$cleanWord])) {
                $cleanWords[] = $this->kamusSlang[$cleanWord];
            } else {
                $cleanWords[] = $cleanWord;
            }
        }

        return implode(' ', $cleanWords);
    }

    private function cekOngkir($kotaInput, $sapaan)
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
            $msg = '🚚 **Ongkir ke '.ucwords($kotaDitemukan)."**\nSekitar Rp ".number_format($harga, 0, ',', '.')." /kg (JNE Reg) {$sapaan}.";
        } else {
            $msg = "Wah, data ongkir ke **{$kotaInput}** belum ada di saya {$sapaan}. Tapi tenang, nanti pas Checkout bakal muncul kok ongkir pastinya.";
        }

        $this->kirimBotResponse($msg);
    }

    private function cekStatusToko($sapaan)
    {
        $jamSekarang = (int) date('H');
        $hariSekarang = date('N');
        $pesanStatus = '';

        if ($hariSekarang <= 6) {
            if ($jamSekarang >= 9 && $jamSekarang < 20) {
                $sisaJam = 20 - $jamSekarang;
                $pesanStatus = "✅ **Toko BUKA** {$sapaan}\nMasih ada waktu {$sisaJam} jam lagi sebelum tutup jam 8 malam.";
            } else {
                $pesanStatus = "⛔ **Toko TUTUP** {$sapaan}\nKita buka lagi besok jam 9 pagi ya.";
            }
        } else {
            if ($jamSekarang >= 10 && $jamSekarang < 18) {
                $pesanStatus = "✅ **Toko BUKA** (Minggu)\nTutup jam 6 sore ya {$sapaan}.";
            } else {
                $pesanStatus = "⛔ **Toko TUTUP**\nSenin kita buka lagi jam 9 pagi.";
            }
        }

        $this->kirimBotResponse($pesanStatus);
    }

    private function cekStatusOrder($orderId)
    {
        $cleanId = str_replace('#', '', $orderId);
        $order = Order::where('id', $cleanId)->orWhere('order_number', 'like', "%{$cleanId}%")->first();

        if ($order) {
            $msg = "📦 **Pesanan #{$order->order_number}**\nStatus: **".strtoupper($order->status)."**\nTotal: Rp ".number_format($order->total_amount, 0, ',', '.');
            if ($order->status == 'shipped') {
                $msg .= "\n\n🚚 Resi: `{$order->shipping_tracking_number}`";
            }
            $this->kirimBotResponse($msg);
        } else {
            $this->kirimBotResponse("Pesanan **#{$cleanId}** gak ketemu nih. Coba cek lagi nomornya ya.");
        }
    }

    private function cariProdukAdvanced($input, $sapaan)
    {
        // Ekstraksi Filter Harga (juta/rb)
        $maxPrice = null;
        $minPrice = null;

        if (preg_match('/(bawah|kurang|max|maksimal)\s*(\d+(\.\d+)?)\s*(jt|juta|ribu|rb)/i', $input, $matches)) {
            $angka = (float) $matches[2];
            $satuan = strtolower($matches[4]);
            $maxPrice = in_array($satuan, ['jt', 'juta']) ? $angka * 1000000 : $angka * 1000;
        }

        if (preg_match('/(atas|lebih|min|minimal)\s*(\d+(\.\d+)?)\s*(jt|juta|ribu|rb)/i', $input, $matches)) {
            $angka = (float) $matches[2];
            $satuan = strtolower($matches[4]);
            $minPrice = in_array($satuan, ['jt', 'juta']) ? $angka * 1000000 : $angka * 1000;
        }

        $rawWords = explode(' ', $input);
        $cleanWords = [];
        $specs = [];

        $stopWords = ['apakah', 'ada', 'jual', 'stok', 'harga', 'berapa', 'yang', 'mau', 'beli', 'cari', 'tolong', 'rekomendasi', 'buat', 'untuk', 'dengan', 'spek', 'spesifikasi', 'dibawah', 'diatas', 'juta', 'ribu', 'jt', 'rb', 'murah', 'mahal', 'dong', 'donk', 'gan', 'sis', 'min'];

        foreach ($rawWords as $word) {
            $wordLower = strtolower($word);
            if (is_numeric($word)) {
                continue;
            }

            if (preg_match('/[0-9]+(gb|tb|hz)/i', $word) || in_array($wordLower, ['ssd', 'hdd', 'ips', 'oled', 'rtx', 'gtx', 'intel', 'amd', 'ryzen', 'core', 'i3', 'i5', 'i7', 'i9'])) {
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

        $query = Product::query()->where('is_active', true);

        if (! empty($cleanWords)) {
            $query->where(function ($q) use ($cleanWords) {
                foreach ($cleanWords as $word) {
                    $q->orWhere('name', 'like', "%{$word}%")
                        ->orWhereHas('kategori', fn ($k) => $k->where('name', 'like', "%{$word}%"));
                }
            });
        }

        if (! empty($specs)) {
            foreach ($specs as $spec) {
                $query->where(function ($q) use ($spec) {
                    $q->where('name', 'like', "%{$spec}%")
                        ->orWhere('description', 'like', "%{$spec}%");
                });
            }
        }

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
            $response = "Nih {$sapaan}, produk yang cocok:\n";
            foreach ($hasil as $p) {
                $stokInfo = $p->stock_quantity > 0 ? '✅ Ada' : '❌ Habis';
                $response .= "\n🔹 **[{$p->name}](".route('toko.produk.detail', $p->id).")**\n   Rp ".number_format($p->sell_price, 0, ',', '.')." | {$stokInfo}";
            }
            if ($maxPrice) {
                $response .= "\n\n_Budget max: Rp ".number_format($maxPrice, 0, ',', '.').'_';
            }

            return $response;
        }

        // Saran Alternatif
        if ($maxPrice || $minPrice) {
            $query->withoutGlobalScopes()->where('is_active', true);
            if (! empty($cleanWords)) {
                $query->where(function ($q) use ($cleanWords) {
                    foreach ($cleanWords as $word) {
                        $q->orWhere('name', 'like', "%{$word}%");
                    }
                });
                $altHasil = $query->take(2)->get();
                if ($altHasil->count() > 0) {
                    $response = "Waduh, budget segitu belum dapet nih {$sapaan}. 😅\n\nTapi ada yang mirip (beda harga dikit):\n";
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
        $kataNegatif = ['kecewa', 'lama', 'lambat', 'penipu', 'rusak', 'batal', 'jelek', 'bodoh', 'anjing', 'babi', 'parah', 'gimana sih', 'tai', 'kampret'];
        foreach ($kataNegatif as $bad) {
            if (str_contains($text, $bad)) {
                return true;
            }
        }

        return false;
    }

    private function jawabanVariatif($sapaan)
    {
        $opsi = [
            "Maaf {$sapaan}, aku masih belajar bahasa gaul nih. 🤔 Coba ketik nama barangnya langsung ya (misal: 'Laptop Asus').",
            "Kurang paham maksudnya {$sapaan}. Mau cari barang, cek resi, atau tanya ongkir?",
            "Waduh, Yala bingung. Coba ketik **'Admin'** aja biar dibantu manusia beneran ya.",
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
