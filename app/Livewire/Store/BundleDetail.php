<?php

namespace App\Livewire\Store;

use App\Models\ProductBundle;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Session;

#[Layout('layouts.store')]
class BundleDetail extends Component
{
    public $bundle;

    public function mount($slug)
    {
        $this->bundle = ProductBundle::with('items.product')->where('slug', $slug)->firstOrFail();
    }

    public function addToCart()
    {
        $cart = Session::get('cart', []);
        
        // Add all items in the bundle to cart
        foreach ($this->bundle->items as $item) {
            $productId = $item->product_id;
            $qty = $item->quantity;
            
            if ($item->product->stock_quantity >= $qty) {
                if (isset($cart[$productId])) {
                    $cart[$productId] += $qty;
                } else {
                    $cart[$productId] = $qty;
                }
            } else {
                $this->dispatch('notify', message: "Stok {$item->product->name} tidak mencukupi untuk bundle ini.", type: 'error');
                return;
            }
        }

        Session::put('cart', $cart);
        $this->dispatch('notify', message: 'Paket berhasil ditambahkan ke keranjang!', type: 'success');
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.store.bundle-detail')->title($this->bundle->name . ' - Paket Hemat Yala Computer');
    }
}
