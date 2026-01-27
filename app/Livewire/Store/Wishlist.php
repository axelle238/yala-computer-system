<?php

namespace App\Livewire\Store;

use App\Models\Wishlist as WishlistModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Keinginan Saya - Yala Computer')]
class Wishlist extends Component
{
    protected $listeners = ['addToWishlist' => 'tambah'];

    public function tambah($idProduk)
    {
        if (! Auth::check()) {
            return redirect()->route('pelanggan.masuk');
        }

        WishlistModel::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $idProduk,
        ]);

        $this->dispatch('notify', message: 'Berhasil ditambahkan ke Keinginan!', type: 'success');
    }

    public function hapus($id)
    {
        WishlistModel::where('id', $id)->where('user_id', Auth::id())->delete();
        $this->dispatch('notify', message: 'Produk dihapus dari daftar keinginan.', type: 'info');
    }

    public function pindahKeKeranjang($id, $idProduk)
    {
        $keranjang = Session::get('cart', []);

        if (isset($keranjang[$idProduk])) {
            $keranjang[$idProduk]++;
        } else {
            $keranjang[$idProduk] = 1;
        }

        Session::put('cart', $keranjang);
        $this->dispatch('cart-updated');

        // Hapus dari wishlist setelah dipindahkan
        $this->hapus($id);

        $this->dispatch('notify', message: 'Produk berhasil dipindahkan ke Keranjang!', type: 'success');
    }

    public function render()
    {
        $item = collect();
        if (Auth::check()) {
            $item = WishlistModel::with('product')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();
        }

        return view('livewire.store.wishlist', ['daftarKeinginan' => $item]);
    }
}