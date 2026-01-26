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
        $pesan = strtolower($pesan);
        $jawaban = '';

        // 1. Sapaan
        if (str_contains($pesan, 'halo') || str_contains($pesan, 'hai') || str_contains($pesan, 'siang') || str_contains($pesan, 'pagi') || str_contains($pesan, 'sore') || str_contains($pesan, 'malam')) {
            $jawaban = "Halo! Saya **YALA**, asisten virtual Yala Computer. 👋\n\nSaya bisa bantu cek stok produk, status pesanan, atau info toko. Mau tanya apa kak?";
        }

        // 2. Info Toko (Jam & Lokasi)
        elseif (str_contains($pesan, 'jam') || str_contains($pesan, 'buka') || str_contains($pesan, 'tutup') || str_contains($pesan, 'operasional')) {
            $jawaban = "**Jam Operasional Yala Computer:**\nSenin - Sabtu: 09:00 - 20:00 WIB\nMinggu: 10:00 - 18:00 WIB\n\nKami melayani pembelian online 24 jam!";
        } elseif (str_contains($pesan, 'lokasi') || str_contains($pesan, 'alamat') || str_contains($pesan, 'toko')) {
            $jawaban = "Toko kami berlokasi di **Jl. Teknologi No. 88, Jakarta Selatan**. \n\nSilakan mampir untuk melihat koleksi PC rakitan kami!";
        }

        // 3. Cek Status Pesanan
        elseif (str_contains($pesan, 'pesanan') || str_contains($pesan, 'order') || str_contains($pesan, 'resi')) {
            // Ekstrak angka dari pesan (asumsi format sederhana)
            if (preg_match('/#?(\d{4,})/', $pesan, $matches)) {
                $orderId = $matches[1];
                // Cari order berdasarkan ID atau Nomor Resi (jika ada kolom resi)
                $order = Order::find($orderId) ?? Order::where('order_number', $orderId)->first();

                if ($order) {
                    $statusLabel = match ($order->status) {
                        'pending' => 'Menunggu Pembayaran',
                        'processing' => 'Sedang Diproses',
                        'shipped' => 'Dalam Pengiriman',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => $order->status
                    };
                    $jawaban = "Status pesanan #{$order->id} saat ini: **{$statusLabel}**.\nTotal: Rp ".number_format($order->total_amount, 0, ',', '.');
                } else {
                    $jawaban = 'Maaf, saya tidak menemukan pesanan dengan nomor tersebut. Pastikan nomor pesanan benar ya.';
                }
            } else {
                $jawaban = "Untuk cek pesanan, silakan ketik nomor pesanan Anda. Contoh: **'Cek pesanan 1023'**.";
            }
        }

        // 4. Eskalasi ke Admin (Handover)
        elseif (str_contains($pesan, 'admin') || str_contains($pesan, 'cs') || str_contains($pesan, 'manusia') || str_contains($pesan, 'komplain') || str_contains($pesan, 'bantuan')) {
            $jawaban = 'Baik, saya akan menghubungkan Anda dengan Customer Service kami. Mohon tunggu sebentar, Admin akan segera membalas.';
            $this->modeBot = false; // Matikan bot untuk sesi ini

            // Kirim Notifikasi ke Admin
            $admins = User::where('role', 'admin')->get(); // Sesuaikan dengan logika role Anda
            if ($admins->count() > 0) {
                Notification::send($admins, new NewChatMessage($pesan, Auth::user()->name ?? 'Tamu'));
            }
        }

        // 5. Cek Stok Produk (Default fallback)
        else {
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
                    $jawaban .= "- **[{$p->name}](".route('toko.produk.detail', $p->id).")**\n  Rp ".number_format($p->sell_price, 0, ',', '.')." | {$stok}\n";
                }
                $jawaban .= "\nKlik nama produk untuk detailnya.";
            } else {
                $jawaban = "Maaf, YALA tidak menemukan produk atau info terkait '{$pesan}'. \n\nCoba kata kunci lain atau ketik **'Admin'** untuk bantuan langsung.";
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
            'daftarPesan' => $this->sesi ? $this->sesi->pesan()->latest()->take(50)->get()->reverse() : collect([]),
        ]);
    }
}
