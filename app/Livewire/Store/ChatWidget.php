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
     * Logika AI Chat "YALA" untuk merespons pelanggan.
     */
    private function prosesBot($pesan)
    {
        $pesanLower = strtolower($pesan);
        $jawaban = '';

        // 0. Cek Pola Order ID
        if (preg_match('/^\d{4,}$/', $pesan) || preg_match('/#(\d{4,})/', $pesan, $matches)) {
            $orderId = $matches[1] ?? $pesan;
            $order = Order::where('id', $orderId)->orWhere('order_number', 'like', "%{$orderId}%")->first();
            
            if ($order) {
                $statusLabel = match ($order->status) {
                    'pending' => 'Menunggu Pembayaran',
                    'processing' => 'Sedang Diproses',
                    'shipped' => 'Dalam Pengiriman',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    default => $order->status
                };
                $jawaban = "Pesanan **#{$order->order_number}** ditemukan!\n\nStatus: **{$statusLabel}**\nTotal: Rp ".number_format($order->total_amount, 0, ',', '.')."\nTanggal: {$order->created_at->format('d M Y')}";
                
                if ($order->status == 'shipped' && $order->shipping_tracking_number) {
                    $jawaban .= "\n\nResi: `{$order->shipping_tracking_number}` ({$order->shipping_courier})";
                }
            }
        }

        // 1. Sapaan & Waktu
        elseif (preg_match('/\b(halo|hai|pagi|siang|sore|malam)\b/', $pesanLower)) {
            $jam = date('H');
            $waktu = $jam < 12 ? 'Pagi' : ($jam < 15 ? 'Siang' : ($jam < 18 ? 'Sore' : 'Malam'));
            $user = Auth::user();
            $nama = $user ? $user->name : 'Kak';
            
            $jawaban = "Halo, Selamat {$waktu} {$nama}! 👋\n\nSaya **YALA**, asisten virtual Yala Computer.\nSilakan tanya stok produk, status pesanan, atau ketik **'Admin'** untuk chat dengan staf kami.";
        }

        // 2. Info Toko
        elseif (str_contains($pesanLower, 'jam') || str_contains($pesanLower, 'buka') || str_contains($pesanLower, 'tutup')) {
            $jawaban = "**Jam Operasional:**\n\nSenin - Sabtu: 09:00 - 20:00 WIB\nMinggu: 10:00 - 18:00 WIB\n\nOrder online tetap buka 24 jam!";
        } elseif (str_contains($pesanLower, 'lokasi') || str_contains($pesanLower, 'alamat')) {
            $jawaban = "Kami berlokasi di:\n**Jl. Teknologi No. 88, Jakarta Selatan**\n\nDatang ya, banyak promo menarik di toko! 🏢";
        }

        // 3. Knowledge Base
        elseif (class_exists('\App\Models\KnowledgeArticle')) {
            $article = \App\Models\KnowledgeArticle::where('title', 'like', "%{$pesan}%")
                ->orWhere('content', 'like', "%{$pesan}%")
                ->where('is_published', true)
                ->first();
            
            if ($article) {
                $jawaban = "Mungkin info ini membantu:\n\n**{$article->title}**\n\n" . Str::limit(strip_tags($article->content), 150) . "\n\n[Baca Detail](" . route('toko.bantuan') . ")";
            }
        }

        // 4. Handover ke Admin
        if (!$jawaban && (str_contains($pesanLower, 'admin') || str_contains($pesanLower, 'cs') || str_contains($pesanLower, 'orang'))) {
            $jawaban = "Baik, saya hubungkan dengan Admin kami ya. Mohon tunggu sebentar...\n\n(Ketik **'Selesai'** jika ingin kembali chat dengan YALA)";
            
            // Set session mode admin
            Session::put('chat_mode_admin_' . $this->sesi->id, true);

            // Notifikasi ke Admin
            $admins = User::where('role', 'admin')->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new NewChatMessage($pesan, Auth::user()->name ?? 'Tamu'));
            }
        }

        // 5. Cek Stok (Fallback)
        if (!$jawaban) {
            $produk = Product::where('name', 'like', '%'.$pesan.'%')
                ->orWhere('sku', 'like', '%'.$pesan.'%')
                ->where('is_active', true)
                ->take(3)
                ->get();

            if ($produk->count() > 0) {
                $jawaban = "Produk yang mungkin Anda cari:\n";
                foreach ($produk as $p) {
                    $stok = $p->stock_quantity > 0 ? "✅ Stok: {$p->stock_quantity}" : "❌ Habis";
                    $jawaban .= "\n- **{$p->name}**\n  Rp ".number_format($p->sell_price, 0, ',', '.')." | {$stok}";
                }
                $jawaban .= "\n\nKlik nama produk untuk detailnya.";
            } else {
                $jawaban = "Maaf, saya kurang paham. Coba kata kunci lain atau ketik **'Admin'** untuk bantuan manusia. 🙏";
            }
        }

        if ($jawaban) {
            PesanObrolan::create([
                'id_sesi' => $this->sesi->id,
                'id_pengguna' => null,
                'is_balasan_admin' => true,
                'isi' => $jawaban,
                'is_dibaca' => true,
            ]);
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
            'isAdminMode' => $this->sesi ? Session::get('chat_mode_admin_' . $this->sesi->id, false) : false,
        ]);
    }
}
