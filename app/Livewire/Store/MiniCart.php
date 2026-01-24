<?php

namespace App\Livewire\Store;

use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\On;

class MiniCart extends Component
{
    public $cart = [];
    public $isOpen = false;

    #[On('cart-updated')]
    public function updateCart()
    {
        $this->cart = Session::get('cart', []);
        
        // Auto open if items added
        // $this->isOpen = true; // Optional: maybe annoying
    }

    #[On('toggle-mini-cart')]
    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->updateCart();
        }
    }

    #[On('addToCart')] // Global listener for add to cart
    public function addToCart($productId, $qty = 1)
    {
        $this->cart = Session::get('cart', []);
        $product = Product::find($productId);

        if (!$product || $product->stock_quantity < 1) {
            $this->dispatch('notify', message: 'Stok barang habis!', type: 'error');
            return;
        }

        if (isset($this->cart[$productId])) {
            $newQty = $this->cart[$productId] + $qty;
            if ($newQty > $product->stock_quantity) {
                 $this->dispatch('notify', message: 'Stok tidak mencukupi!', type: 'error');
                 return;
            }
            $this->cart[$productId] = $newQty;
        } else {
            $this->cart[$productId] = $qty;
        }

        Session::put('cart', $this->cart);
        $this->dispatch('notify', message: 'Ditambahkan ke keranjang!', type: 'success');
        $this->isOpen = true; // Auto open
    }

    public function removeItem($id)
    {
        unset($this->cart[$id]);
        Session::put('cart', $this->cart);
        $this->dispatch('cart-updated');
    }

    public function updateQty($id, $change)
    {
        if (!isset($this->cart[$id])) return;
        
        $newQty = $this->cart[$id] + $change;
        $product = Product::find($id);

        if ($newQty > 0 && $newQty <= $product->stock_quantity) {
            $this->cart[$id] = $newQty;
            Session::put('cart', $this->cart);
        } elseif ($newQty <= 0) {
            $this->removeItem($id);
        }
    }

    public function render()
    {
        $products = [];
        $total = 0;

        if (!empty($this->cart)) {
            $products = Product::whereIn('id', array_keys($this->cart))->get()->map(function($p) {
                $p->cart_qty = $this->cart[$p->id];
                return $p;
            });
            
            foreach ($products as $p) {
                $total += $p->sell_price * $p->cart_qty;
            }
        }

        return view('livewire.store.mini-cart', [
            'products' => $products,
            'total' => $total,
            'count' => count($this->cart)
        ]);
    }
}
