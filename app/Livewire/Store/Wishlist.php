<?php

namespace App\Livewire\Store;

use App\Models\Wishlist as WishlistModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Wishlist Saya - Yala Computer')]
class Wishlist extends Component
{
    protected $listeners = ['addToWishlist' => 'add'];

    public function add($productId)
    {
        if (! Auth::check()) {
            return redirect()->route('pelanggan.masuk');
        }

        WishlistModel::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $productId,
        ]);

        $this->dispatch('notify', message: 'Ditambahkan ke Wishlist!', type: 'success');
    }

    public function remove($id)
    {
        WishlistModel::where('id', $id)->where('user_id', Auth::id())->delete();
        $this->dispatch('notify', message: 'Dihapus dari Wishlist.', type: 'info');
    }

    public function moveToCart($id, $productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]++;
        } else {
            $cart[$productId] = 1;
        }

        Session::put('cart', $cart);
        $this->dispatch('cart-updated');

        // Remove from wishlist after moving
        $this->remove($id);

        $this->dispatch('notify', message: 'Produk dipindahkan ke Keranjang!', type: 'success');
    }

    public function render()
    {
        $items = collect();
        if (Auth::check()) {
            $items = WishlistModel::with('product')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();
        }

        return view('livewire.store.wishlist', ['items' => $items]);
    }
}
