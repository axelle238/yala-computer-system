<?php

namespace App\Livewire\Store;

use App\Models\SesiObrolan;
use App\Models\PesanObrolan;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\On;

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
        
        if (!$this->tokenTamu && !Auth::check()) {
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
        $this->terbuka = !$this->terbuka;
        if ($this->terbuka) {
            $this->tandaiDibaca();
        }
    }

    public function kirimPesan()
    {
        $this->validate(['pesanBaru' => 'required|string|min:1']);

        // Buat sesi jika belum ada
        if (!$this->sesi) {
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
            'is_dibaca' => false
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
     * Logika AI Chat sederhana untuk merespons pertanyaan produk.
     */
    private function prosesBot($pesan)
    {
        $pesan = strtolower($pesan);
        $jawaban = '';

        // Cek Kata Kunci: "Halo", "Hai"
        if (str_contains($pesan, 'halo') || str_contains($pesan, 'hai') || str_contains($pesan, 'siang') || str_contains($pesan, 'pagi')) {
            $jawaban = "Halo! Saya Asisten Virtual Yala. Ada yang bisa saya bantu? Ketik nama produk untuk cek stok.";
        }
        // Cek Kata Kunci: "Admin", "CS", "Orang"
        elseif (str_contains($pesan, 'admin') || str_contains($pesan, 'cs') || str_contains($pesan, 'manusia')) {
            $jawaban = "Baik, saya akan menghubungkan Anda dengan Customer Service kami. Mohon tunggu sebentar.";
            $this->modeBot = false; // Matikan bot untuk sesi ini
            // Di sini bisa ditambahkan notifikasi ke dashboard admin (todo)
        }
        // Cek Stok Produk
        else {
            // Coba cari produk berdasarkan kata kunci di pesan
            $produk = Product::where('name', 'like', '%' . $pesan . '%')
                ->orWhere('sku', 'like', '%' . $pesan . '%')
                ->where('is_active', true)
                ->first();

            if ($produk) {
                $jawaban = "Produk **{$produk->name}** tersedia dengan stok **{$produk->stock_quantity} unit**. Harganya **Rp " . number_format($produk->sell_price, 0, ',', '.') . "**. Apakah Anda ingin memesannya?";
            } else {
                $jawaban = "Maaf, saya tidak menemukan produk tersebut. Coba kata kunci lain atau ketik 'Admin' untuk bantuan staf.";
            }
        }

        // Simpan Balasan Bot
        if ($jawaban) {
            PesanObrolan::create([
                'id_sesi' => $this->sesi->id,
                'id_pengguna' => null, // Bot = Sistem
                'is_balasan_admin' => true, // Dianggap admin reply
                'isi' => $jawaban,
                'is_dibaca' => true
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
            'daftarPesan' => $this->sesi ? $this->sesi->pesan()->latest()->take(50)->get()->reverse() : []
        ]);
    }
}