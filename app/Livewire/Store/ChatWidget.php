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
    /**
     * Status tampilan widget chat.
     */
    public $terbuka = false;

    /**
     * Sesi obrolan aktif.
     */
    public $sesi;

    /**
     * Input pesan baru.
     */
    public $pesanBaru = '';

    /**
     * Token identitas tamu.
     */
    public $tokenTamu;

    /**
     * Status mode bot AI.
     */
    public $modeBot = true;

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

        // Pastikan token tamu ada
        if (! $this->tokenTamu && ! Auth::check()) {
            $this->tokenTamu = Session::get('token_tamu_chat');
        }

        // Buat sesi jika belum ada
        if (! $this->sesi) {
            $this->sesi = SesiObrolan::create([
                'id_pelanggan' => Auth::id(),
                'token_tamu' => Auth::check() ? null : $this->tokenTamu,
                'topik' => 'Obrolan Baru',
            ]);
        }

        // Cek Mode Admin
        $isAdminMode = Session::get('chat_mode_admin_' . $this->sesi->id, false);

        // Simpan Pesan Pengguna
        PesanObrolan::create([
            'id_sesi' => $this->sesi->id,
            'id_pengguna' => Auth::id(),
            'is_balasan_admin' => false,
            'isi' => $this->pesanBaru,
            'is_dibaca' => false,
        ]);

        $pesanTerkirim = $this->pesanBaru;
        $this->pesanBaru = '';

        // Jika user minta berhenti chat admin
        if ($isAdminMode && (strtolower($pesanTerkirim) == 'selesai' || str_contains(strtolower($pesanTerkirim), 'kembali ke bot'))) {
            Session::forget('chat_mode_admin_' . $this->sesi->id);
            $isAdminMode = false;
            
            // Bot menyapa kembali
            usleep(500000); // 0.5s delay
            PesanObrolan::create([
                'id_sesi' => $this->sesi->id,
                'id_pengguna' => null,
                'is_balasan_admin' => true,
                'isi' => "Oke, YALA kembali di sini! Ada yang bisa saya bantu lagi?",
                'is_dibaca' => true,
            ]);
            $this->dispatch('pesan-terkirim');
            return;
        }

        // Logika Bot AI (Hanya jika TIDAK dalam mode admin)
        if ($this->modeBot && ! $isAdminMode) {
            // Simulasi berpikir (Delay 0.5 - 1.5 detik)
            usleep(rand(500000, 1500000));
            
            $this->prosesBot($pesanTerkirim);
        }

        $this->dispatch('pesan-terkirim');
    }

    public function akhiriChatAdmin()
    {
        if ($this->sesi) {
            Session::forget('chat_mode_admin_' . $this->sesi->id);
            PesanObrolan::create([
                'id_sesi' => $this->sesi->id,
                'id_pengguna' => null,
                'is_balasan_admin' => true,
                'isi' => "Sesi dengan Admin telah diakhiri. YALA siap membantu kembali!",
                'is_dibaca' => true,
            ]);
            $this->dispatch('pesan-terkirim');
        }
    }

    /**
     * Logika AI Chat "YALA" Generasi 2 (Lebih Manusiawi).
     */
    private function prosesBot($pesan)
    {
        $pesanLower = strtolower($pesan);
        $jawaban = '';

        // 1. Analisis Sentimen Sederhana
        $isMarah = $this->analisisSentimen($pesanLower);
        if ($isMarah) {
            $jawaban = "Waduh, saya merasakan ketidaknyamanan Anda. 😟 Mohon maaf ya Kak. Bisa ceritakan detail masalahnya? Atau ketik **'Admin'** agar saya hubungkan langsung dengan Supervisor kami.";
            $this->kirimBotResponse($jawaban);
            return;
        }

        // 2. Deteksi Topik Spesifik
        if (str_contains($pesanLower, 'lacak') || str_contains($pesanLower, 'resi') || str_contains($pesanLower, 'sampai mana')) {
            $jawaban = "Siap bantu lacak! 🚚\nSilakan ketik **Nomor Pesanan** (contoh: #1234) atau Nomor Resi Anda di sini.";
        }
        elseif (str_contains($pesanLower, 'rakit') || str_contains($pesanLower, 'pc')) {
            $jawaban = "Wah, mau rakit PC ya? Pilihan tepat! 🖥️\nKami punya fitur simulasi rakit PC. Klik di sini: [Mulai Rakit PC](" . route('toko.rakit-pc') . ")\n\nAtau butuh rekomendasi spesifikasi untuk gaming/editing?";
        }
        elseif (str_contains($pesanLower, 'garansi') || str_contains($pesanLower, 'rusak') || str_contains($pesanLower, 'klaim')) {
            $jawaban = "Untuk klaim garansi atau servis, Kakak bisa bawa unitnya ke toko kami.\nPastikan bawa nota pembelian ya. 🛠️\n\nInfo lengkap cek di: [Cek Garansi](" . route('toko.cek-garansi') . ")";
        }
        // Cek Order ID (Regex)
        elseif (preg_match('/\d{4,}/', $pesan, $matches)) {
            $this->cekStatusOrder($matches[0]);
            return;
        }

        // 3. Sapaan & Obrolan Ringan (Chit-Chat)
        elseif (preg_match('/\b(halo|hai|pagi|siang|sore|malam|test|tes)\b/', $pesanLower)) {
            $jam = date('H');
            $sapaan = $jam < 12 ? 'Pagi' : ($jam < 15 ? 'Siang' : ($jam < 18 ? 'Sore' : 'Malam'));
            $user = Auth::user();
            $nama = $user ? explode(' ', $user->name)[0] : 'Kak';
            $jawaban = "Halo, Selamat {$sapaan} {$nama}! 👋\nSenang bertemu Anda. Ada yang bisa Yala bantu cari hari ini? (Laptop, VGA, atau status paket?)";
        }

        // 4. Pencarian Produk Cerdas (Inti Fitur)
        if (empty($jawaban)) {
            $jawaban = $this->cariProdukCerdas($pesan);
        }

        // 5. Handover ke Admin (Jika user minta eksplisit)
        if (str_contains($pesanLower, 'admin') || str_contains($pesanLower, 'cs') || str_contains($pesanLower, 'orang')) {
            $jawaban = "Oke, saya panggilkan Admin CS kami ya. Mohon tunggu sebentar... ⏳\n(Nanti chat ini akan dibalas manusia asli)";
            Session::put('chat_mode_admin_' . $this->sesi->id, true);
            
            // Notif Admin (Opsional)
            // ...
        }

        // 6. Fallback Terakhir (Jangan Menolak!)
        if (empty($jawaban)) {
            $jawaban = $this->jawabanVariatif('bingung');
        }

        $this->kirimBotResponse($jawaban);
    }

    private function cekStatusOrder($orderId)
    {
        // Bersihkan ID (hapus # jika ada)
        $cleanId = str_replace('#', '', $orderId);
        $order = Order::where('id', $cleanId)->orWhere('order_number', 'like', "%{$cleanId}%")->first();

        if ($order) {
            $msg = "Ketemu! Pesanan **#{$order->order_number}**\nStatus: **" . strtoupper($order->status) . "**\n";
            if ($order->status == 'shipped') {
                $msg .= "Resi: `{$order->shipping_tracking_number}`\nEkspedisi: {$order->shipping_courier}";
            } elseif ($order->status == 'pending') {
                $msg .= "Mohon segera lakukan pembayaran ya Kak.";
            }
            $this->kirimBotResponse($msg);
        } else {
            $this->kirimBotResponse("Hmm, saya cari pesanan #{$cleanId} kok belum ketemu ya? 🤔 Pastikan angkanya benar ya Kak.");
        }
    }

    private function cariProdukCerdas($input)
    {
        // Hapus kata-kata umum (stop words)
        $stopWords = ['apakah', 'ada', 'jual', 'stok', 'harga', 'berapa', 'yang', 'mau', 'beli', 'cari', 'tolong'];
        $keywords = collect(explode(' ', strtolower($input)))
            ->reject(fn($w) => in_array($w, $stopWords) || strlen($w) < 3)
            ->values();

        if ($keywords->isEmpty()) return null;

        // Cari Produk (Query fleksibel)
        $query = Product::query()->where('is_active', true);
        
        $query->where(function($q) use ($keywords) {
            foreach ($keywords as $word) {
                $q->orWhere('name', 'like', "%{$word}%")
                  ->orWhere('description', 'like', "%{$word}%")
                  ->orWhereHas('kategori', fn($k) => $k->where('name', 'like', "%{$word}%"));
            }
        });

        $hasil = $query->take(3)->get();

        if ($hasil->count() > 0) {
            $response = "Saya menemukan beberapa produk yang mungkin cocok:\n";
            foreach ($hasil as $p) {
                $stokInfo = $p->stock_quantity > 0 ? "✅ Ready" : "❌ Habis";
                $response .= "\n🔹 **[{$p->name}](" . route('toko.produk.detail', $p->id) . ")**\n   Rp " . number_format($p->sell_price, 0, ',', '.') . " | {$stokInfo}";
            }
            $response .= "\n\nKlik nama produk untuk detail spesifikasinya ya Kak.";
            return $response;
        }

        return null;
    }

    private function analisisSentimen($text)
    {
        $kataNegatif = ['kecewa', 'lama', 'lambat', 'penipu', 'rusak', 'batal', 'jelek', 'bodoh', 'anjing', 'babi'];
        foreach ($kataNegatif as $bad) {
            if (str_contains($text, $bad)) return true;
        }
        return false;
    }

    private function jawabanVariatif($tipe)
    {
        $opsi = [];
        if ($tipe == 'bingung') {
            $opsi = [
                "Hmm, saya sedang belajar memahami itu. 🤔 Bisa gunakan kata kunci yang lebih sederhana? (misal: 'Laptop Asus', 'Cek Resi')",
                "Saya kurang yakin maksudnya, tapi saya siap bantu carikan produk. Coba sebutkan nama barangnya?",
                "Waduh, Yala belum diajari tentang itu. Tapi kalau mau tanya stok atau rakit PC, Yala jagonya! 😎",
                "Maaf Kak, bisa diulangi? Saya ingin memastikan saya memberikan info yang tepat."
            ];
        }

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

    /**
     * Logika AI Chat "YALA" untuk merespons pelanggan. (DEPRECATED - Diganti prosesBot Baru)
     */
    private function prosesBot_OLD($pesan)
    {
        // ... (Kode Lama) ...
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
            'isAdminMode' => $this->sesi ? Session::get('chat_mode_admin_' . $this->sesi->id, false) : false,
        ]);
    }
}
