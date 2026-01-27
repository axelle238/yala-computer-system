<?php

namespace App\Livewire\Store;

use App\Models\Banner;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\Product;
use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Pusat Belanja Komputer & Rakit PC - Yala Computer')]
class Home extends Component
{
    // Input Lacak
    public $nomorLacakTiket = '';

    /**
     * Mengarahkan pengguna ke halaman pelacakan servis.
     */
    public function lacakServis()
    {
        $this->validate([
            'nomorLacakTiket' => 'required|string|min:5',
        ], [
            'nomorLacakTiket.required' => 'Masukkan nomor tiket servis.',
            'nomorLacakTiket.min' => 'Nomor tiket tidak valid.',
        ]);

        return redirect()->route('toko.lacak-servis', ['ticket' => $this->nomorLacakTiket]);
    }

    /**
     * Menambahkan item ke keranjang belanja sesi.
     */
    public function tambahKeKeranjang($idProduk)
    {
        $keranjang = session()->get('cart', []);
        if (isset($keranjang[$idProduk])) {
            $keranjang[$idProduk]++;
        } else {
            $keranjang[$idProduk] = 1;
        }

        session()->put('cart', $keranjang);

        $this->dispatch('cart-updated');
        $this->dispatch('notify', message: 'Item berhasil masuk ke keranjang belanja.', type: 'success');
    }

    /**
     * Menambahkan produk ke daftar perbandingan.
     */
    public function tambahKePerbandingan($idProduk)
    {
        $daftarBanding = session()->get('comparison_list', []);

        if (! in_array($idProduk, $daftarBanding)) {
            if (count($daftarBanding) >= 4) {
                $this->dispatch('notify', message: 'Batas perbandingan maksimal adalah 4 produk.', type: 'error');

                return;
            }
            $daftarBanding[] = $idProduk;
            session()->put('comparison_list', $daftarBanding);
            $this->dispatch('notify', message: 'Produk ditambahkan ke daftar banding.', type: 'success');
        } else {
            $this->dispatch('notify', message: 'Produk sudah ada dalam daftar banding Anda.', type: 'info');
        }
    }

    public function render()
    {
        $daftarBanner = Banner::where('is_active', true)
            ->orderBy('order')
            ->get();

        $daftarKategori = Category::withCount('produk')
            ->where('is_active', true)
            ->get();

        $flashSaleStatus = Setting::get('flash_sale_active', true);

        $daftarObralKilat = FlashSale::with('product')
            ->where('is_active', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->where('quota', '>', 0)
            ->take(4)
            ->get();

        $daftarProdukBaru = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        return view('livewire.store.home', [
            'banner' => $daftarBanner,
            'kategori' => $daftarKategori,
            'produk' => $daftarProdukBaru,
            'daftarFlashSale' => $daftarObralKilat,
            'flashSaleAktif' => $flashSaleStatus,
        ]);
    }
}
