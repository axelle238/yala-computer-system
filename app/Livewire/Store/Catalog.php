<?php

namespace App\Livewire\Store;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.store')]
#[Title('Katalog Produk Lengkap - Yala Computer')]
class Catalog extends Component
{
    use WithPagination;

    public $cari = '';

    public $kategori = '';

    public $urutkan = 'terbaru'; // latest, harga_rendah, harga_tinggi

    public $hargaMin = 0;

    public $hargaMaks = 50000000;

    public function updatedCari()
    {
        $this->resetPage();
    }

    public function updatedKategori()
    {
        $this->resetPage();
    }

    public function updatedUrutkan()
    {
        $this->resetPage();
    }

    public function tambahKeKeranjang($id)
    {
        $keranjang = session()->get('cart', []);
        if (isset($keranjang[$id])) {
            $keranjang[$id]++;
        } else {
            $keranjang[$id] = 1;
        }
        session()->put('cart', $keranjang);
        $this->dispatch('cart-updated');
        $this->dispatch('notify', message: 'Produk ditambahkan ke keranjang!', type: 'success');
    }

    public function tambahKeBandingkan($id)
    {
        $bandingkan = session()->get('compare_products', []);

        if (in_array($id, $bandingkan)) {
            $this->dispatch('notify', message: 'Produk sudah ada di perbandingan.', type: 'info');

            return;
        }

        if (count($bandingkan) >= 4) {
            $this->dispatch('notify', message: 'Maksimal 4 produk untuk dibandingkan.', type: 'error');

            return;
        }

        $bandingkan[] = $id;
        session()->put('compare_products', $bandingkan);
        $this->dispatch('notify', message: 'Ditambahkan ke perbandingan!', type: 'success');
    }

    public function render()
    {
        $produk = Product::query()
            ->where('is_active', true)
            ->when($this->cari, function ($q) {
                $q->where('name', 'like', '%'.$this->cari.'%')
                    ->orWhere('sku', 'like', '%'.$this->cari.'%');
            })
            ->when($this->kategori, function ($q) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $this->kategori));
            })
            ->whereBetween('sell_price', [$this->hargaMin, $this->hargaMaks])
            ->when($this->urutkan === 'terbaru', fn ($q) => $q->latest())
            ->when($this->urutkan === 'harga_rendah', fn ($q) => $q->orderBy('sell_price', 'asc'))
            ->when($this->urutkan === 'harga_tinggi', fn ($q) => $q->orderBy('sell_price', 'desc'))
            ->paginate(12);

        $daftarKategori = Category::where('is_active', true)->get();

        return view('livewire.store.catalog', [
            'produk' => $produk,
            'daftarKategori' => $daftarKategori,
        ]);
    }
}
