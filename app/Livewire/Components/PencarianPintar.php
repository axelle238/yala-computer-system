<?php

namespace App\Livewire\Components;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;

class PencarianPintar extends Component
{
    public $cari = '';

    public $hasil = [];

    public $terbuka = false;

    // Listen to keyboard shortcut from global JS
    protected $listeners = ['open-spotlight' => 'buka'];

    public function buka()
    {
        $this->terbuka = true;
        $this->dispatch('focus-spotlight');
    }

    public function tutup()
    {
        $this->terbuka = false;
        $this->cari = '';
        $this->hasil = [];
    }

    public function updatedCari()
    {
        if (strlen($this->cari) < 2) {
            $this->hasil = [];

            return;
        }

        $this->hasil = [
            'Menu' => $this->cariMenu(),
            'Produk' => $this->cariProduk(),
            'Pelanggan' => $this->cariPelanggan(),
            'Pesanan' => $this->cariPesanan(),
        ];
    }

    private function cariMenu()
    {
        $menu = [
            ['label' => 'Dashboard', 'route' => 'admin.beranda', 'icon' => 'home'],
            ['label' => 'Kasir (POS)', 'route' => 'admin.kasir', 'icon' => 'shopping-cart'],
            ['label' => 'Daftar Produk', 'route' => 'admin.produk.indeks', 'icon' => 'tag'],
            ['label' => 'Stok Opname', 'route' => 'admin.gudang.stok-opname', 'icon' => 'clipboard-check'],
            ['label' => 'Pesanan Pembelian', 'route' => 'admin.pesanan-pembelian.indeks', 'icon' => 'truck'],
            ['label' => 'Papan Servis', 'route' => 'admin.servis.indeks', 'icon' => 'wrench'],
            ['label' => 'Laporan Keuangan', 'route' => 'admin.keuangan.laba-rugi', 'icon' => 'chart-pie'],
            ['label' => 'Manajemen Karyawan', 'route' => 'admin.karyawan.indeks', 'icon' => 'users'],
            ['label' => 'Pengaturan Sistem', 'route' => 'admin.pengaturan', 'icon' => 'cog'],
        ];

        return collect($menu)->filter(function ($m) {
            return stripos($m['label'], $this->cari) !== false;
        })->take(3)->all();
    }

    private function cariProduk()
    {
        return Product::where('name', 'like', '%'.$this->cari.'%')
            ->orWhere('sku', 'like', '%'.$this->cari.'%')
            ->take(3)
            ->get()
            ->map(fn ($p) => [
                'label' => $p->name,
                'sub' => 'Stok: '.$p->stock_quantity,
                'route' => route('admin.produk.ubah', $p->id),
                'icon' => 'box',
            ]);
    }

    private function cariPelanggan()
    {
        return Customer::where('name', 'like', '%'.$this->cari.'%')
            ->orWhere('phone', 'like', '%'.$this->cari.'%')
            ->take(3)
            ->get()
            ->map(fn ($c) => [
                'label' => $c->name,
                'sub' => $c->phone,
                'route' => route('admin.pelanggan.ubah', $c->id),
                'icon' => 'user',
            ]);
    }

    private function cariPesanan()
    {
        return Order::where('order_number', 'like', '%'.$this->cari.'%')
            ->take(3)
            ->get()
            ->map(fn ($o) => [
                'label' => 'Pesanan #'.$o->order_number,
                'sub' => $o->status_label.' - '.($o->guest_name ?? $o->pengguna->name ?? 'Tamu'),
                'route' => route('admin.pesanan.tampil', $o->id),
                'icon' => 'receipt-tax',
            ]);
    }

    public function render()
    {
        return view('livewire.components.pencarian-pintar');
    }
}
