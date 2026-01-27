<?php

namespace App\Livewire\Components;

use App\Models\Order;
use App\Models\Product;
use App\Models\ServiceTicket;
use Livewire\Component;

class AdminNotification extends Component
{
    public $terbuka = false;

    public $notifikasi = [];

    public $adaBelumDibaca = false;

    public function mount()
    {
        $this->ambilNotifikasi();
    }

    public function ambilNotifikasi()
    {
        $daftarNotif = [];

        // 1. Peringatan Stok Menipis (Ambil 5 teratas)
        $stokTipis = Product::whereColumn('stock_quantity', '<=', 'min_stock_alert')
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->take(5)
            ->get();

        foreach ($stokTipis as $p) {
            $daftarNotif[] = [
                'id' => 'stok-'.$p->id,
                'tipe' => 'warning',
                'judul' => 'Stok Menipis',
                'pesan' => "Produk {$p->name} tersisa {$p->stock_quantity} unit.",
                'waktu' => now(), // Seharusnya last updated, tapi ok untuk alert real-time
                'rute' => route('admin.produk.ubah', $p->id),
            ];
        }

        // 2. Pesanan Baru (Pending)
        $pesananBaru = Order::where('status', 'pending')->latest()->take(5)->get();
        foreach ($pesananBaru as $o) {
            $daftarNotif[] = [
                'id' => 'pesanan-'.$o->id,
                'tipe' => 'success',
                'judul' => 'Pesanan Baru Masuk',
                'pesan' => "#{$o->order_number} dari {$o->guest_name} senilai Rp ".number_format($o->total_amount, 0, ',', '.'),
                'waktu' => $o->created_at,
                'rute' => route('admin.pesanan.tampil', $o->id),
            ];
        }

        // 3. Tiket Servis Baru (Pending)
        $servisBaru = ServiceTicket::where('status', 'pending')->latest()->take(3)->get();
        foreach ($servisBaru as $s) {
            $daftarNotif[] = [
                'id' => 'servis-'.$s->id,
                'tipe' => 'info',
                'judul' => 'Tiket Servis Baru',
                'pesan' => "Tiket #{$s->ticket_number} ({$s->device_name})",
                'waktu' => $s->created_at,
                'rute' => route('admin.servis.meja-kerja', $s->id),
            ];
        }

        // Urutkan berdasarkan waktu terbaru
        usort($daftarNotif, fn ($a, $b) => $b['waktu'] <=> $a['waktu']);

        $this->notifikasi = $daftarNotif;
        $this->adaBelumDibaca = count($this->notifikasi) > 0;
    }

    public function toggle()
    {
        $this->terbuka = ! $this->terbuka;
        if ($this->terbuka) {
            // Segarkan data saat dibuka
            $this->ambilNotifikasi();
        }
    }

    public function tandaiDibaca()
    {
        // Dalam implementasi database real, ini akan update status 'read_at'
        // Untuk sekarang, kita kosongkan list sementara di sesi ini
        $this->notifikasi = [];
        $this->adaBelumDibaca = false;
        $this->terbuka = false;
        $this->dispatch('notify', message: 'Notifikasi disembunyikan sementara.', type: 'info');
    }

    public function render()
    {
        return view('livewire.components.admin-notification');
    }
}
