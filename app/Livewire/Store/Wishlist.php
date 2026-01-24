<?php

namespace App\Livewire\Store;

use App\Models\Wishlist as WishlistModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Favorit Saya (Wishlist) - Yala Computer')]
class Wishlist extends Component
{
    public function remove($id)
    {
        WishlistModel::where('id', $id)->where('user_id', Auth::id())->delete();
        $this->dispatch('notify', message: 'Produk dihapus dari wishlist.', type: 'success');
    }

    public function moveToCart($id)
    {
        $item = WishlistModel::with('product')->find($id);
        
        if ($item && $item->product) {
            if ($item->product->stock_quantity > 0) {
                $this->dispatch('addToCart', productId: $item->product_id); // Trigger global cart
                $item->delete(); // Remove from wishlist after moving
            } else {
                $this->dispatch('notify', message: 'Stok produk habis.', type: 'error');
            }
        }
    }

    public function render()
    {
        $items = Auth::check() 
            ? WishlistModel::with('product.category')->where('user_id', Auth::id())->latest()->get() 
            : collect();

        return view('livewire.store.wishlist', [
            'items' => $items
        ]);
    }
}
