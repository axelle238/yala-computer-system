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

        // Simpan Pesan Pengguna
        PesanObrolan::create([
            'id_sesi' => $this->sesi->id,
            'id_pengguna' => Auth::id(), // Null jika tamu
            'is_balasan_admin' => false,
            'isi' => $this->pesanBaru,
            'is_dibaca' => false,
        ]);

        $pesanTerkirim = $this->pesanBaru;
        $this->pesanBaru = '';

        // Logika Bot AI Sederhana
        if ($this->modeBot) {
            $this->prosesBot($pesanTerkirim);
        }

        $this->dispatch('pesan-terkirim'); // Event untuk scroll ke bawah
    }

    /**
     * Logika AI Chat "YALA" untuk merespons pelanggan.
     */
    private function prosesBot($pesan)
    {
        $pesanLower = strtolower($pesan);
        $jawaban = '';

        // 0. Cek Pola Order ID (Angka 4 digit atau lebih)
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

        // 1. Sapaan
        elseif (str_contains($pesanLower, 'halo') || str_contains($pesanLower, 'hai') || str_contains($pesanLower, 'pagi') || str_contains($pesanLower, 'siang') || str_contains($pesanLower, 'sore') || str_contains($pesanLower, 'malam')) {
            $user = Auth::user();
            $sapaan = $user ? "Halo, **{$user->name}**! 👋" : "Halo! 👋";
            $jawaban = "{$sapaan}\n\nSaya **YALA**, asisten virtual Yala Computer.\n\nKetik nomor pesanan untuk cek status, atau nama produk untuk cek stok.";
        }

        // 2. Info Toko (Jam & Lokasi)
        elseif (str_contains($pesanLower, 'jam') || str_contains($pesanLower, 'buka') || str_contains($pesanLower, 'tutup') || str_contains($pesanLower, 'operasional')) {
            $jawaban = "**Jam Operasional Yala Computer:**\n\nSenin - Sabtu: 09:00 - 20:00 WIB\nMinggu: 10:00 - 18:00 WIB\n\nKami melayani pembelian online 24 jam!";
        } elseif (str_contains($pesanLower, 'lokasi') || str_contains($pesanLower, 'alamat') || str_contains($pesanLower, 'toko')) {
            $jawaban = "Toko kami berlokasi di **Jl. Teknologi No. 88, Jakarta Selatan**.\n\nSilakan mampir untuk melihat koleksi PC rakitan kami!";
        }

        // 3. Knowledge Base Lookup
        elseif (class_exists('\App\Models\KnowledgeArticle')) {
            $article = \App\Models\KnowledgeArticle::where('title', 'like', "%{$pesan}%")
                ->orWhere('content', 'like', "%{$pesan}%")
                ->where('is_published', true)
                ->first();
            
            if ($article) {
                $jawaban = "Saya menemukan artikel yang mungkin membantu:\n\n**{$article->title}**\n\n" . Str::limit(strip_tags($article->content), 200) . "\n\n[Baca Selengkapnya](" . route('toko.bantuan') . ")";
            }
        }

        // 4. Eskalasi ke Admin (Handover)
        if (!$jawaban && (str_contains($pesanLower, 'admin') || str_contains($pesanLower, 'cs') || str_contains($pesanLower, 'manusia') || str_contains($pesanLower, 'komplain') || str_contains($pesanLower, 'bantuan'))) {
            $jawaban = 'Baik, saya akan menghubungkan Anda dengan Customer Service kami. Mohon tunggu sebentar, Admin akan segera membalas.';
            $this->modeBot = false; // Matikan bot untuk sesi ini

            // Kirim Notifikasi ke Admin
            $admins = User::where('role', 'admin')->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new NewChatMessage($pesan, Auth::user()->name ?? 'Tamu'));
            }
        }

        // 5. Cek Stok Produk (Default fallback)
        if (!$jawaban) {
            // Cari produk
            $produk = Product::where('name', 'like', '%'.$pesan.'%')
                ->orWhere('sku', 'like', '%'.$pesan.'%')
                ->where('is_active', true)
                ->take(3)
                ->get();

            if ($produk->count() > 0) {
                $jawaban = "Saya menemukan produk yang cocok:\n";
                foreach ($produk as $p) {
                    $stok = $p->stock_quantity > 0 ? "Stok: {$p->stock_quantity}" : 'Stok Habis';
                    $jawaban .= "\n- **[{$p->name}](".route('toko.produk.detail', $p->id).")**\n  Rp ".number_format($p->sell_price, 0, ',', '.')." | {$stok}";
                }
                $jawaban .= "\n\nKlik nama produk untuk detailnya.";
            } else {
                $jawaban = "Maaf, YALA tidak menemukan produk atau info terkait '{$pesan}'.\n\nCoba kata kunci lain, ketik nomor pesanan, atau ketik **'Admin'** untuk bantuan langsung.";
            }
        }

        // Simpan Balasan Bot
        if ($jawaban) {
            PesanObrolan::create([
                'id_sesi' => $this->sesi->id,
                'id_pengguna' => null, // Bot = Sistem
                'is_balasan_admin' => true, // Dianggap admin reply
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
        ]);
    }
}
