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
#[Title('Yala Computer - Toko Komputer Terlengkap')]
class Home extends Component
{
    public $nomorLacak = '';

    public function lacakServis()
    {
        $this->validate(['nomorLacak' => 'required|string|min:5']);

        return redirect()->route('toko.lacak-servis', ['ticket' => $this->nomorLacak]);
    }

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
        $this->dispatch('notify', message: 'Produk ditambahkan ke keranjang!', type: 'success');
    }

    public function tambahKePerbandingan($idProduk)
    {
        $perbandingan = session()->get('comparison_list', []);
        if (! in_array($idProduk, $perbandingan)) {
            if (count($perbandingan) >= 4) {
                $this->dispatch('notify', message: 'Maksimal 4 produk untuk dibandingkan.', type: 'error');

                return;
            }
            $perbandingan[] = $idProduk;
            session()->put('comparison_list', $perbandingan);
            $this->dispatch('notify', message: 'Ditambahkan ke perbandingan!', type: 'success');
        } else {
            $this->dispatch('notify', message: 'Produk sudah ada di list.', type: 'info');
        }
    }

    public function render()
    {
        $banner = Banner::where('is_active', true)
            ->orderBy('order')
            ->get();

        $kategori = Category::withCount('products')
            ->where('is_active', true)
            ->get();

        $flashSaleAktif = Setting::get('flash_sale_active', true); // Toggle global

        $daftarFlashSale = FlashSale::with('product')
            ->where('is_active', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->where('quota', '>', 0)
            ->take(4)
            ->get();

        $produk = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        return view('livewire.store.home', [
            'banner' => $banner,
            'kategori' => $kategori,
            'produk' => $produk,
            'daftarFlashSale' => $daftarFlashSale,
            'flashSaleAktif' => $flashSaleAktif,
        ]);
    }
}
