<?php

namespace App\Livewire\Store;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;

class QuickView extends Component
{
    public $isOpen = false;
    public $product = null;

    #[On('openQuickView')]
    public function open($productId)
    {
        $this->product = Product::find($productId);
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
        $this->product = null;
    }

    public function addToCart()
    {
        if ($this->product) {
            $this->dispatch('addToCart', productId: $this->product->id);
            $this->close();
        }
    }

    public function render()
    {
        return view('livewire.store.quick-view');
    }
}
