<?php

namespace App\Livewire\Store;

use App\Models\Product;
use Livewire\Component;

class QuickView extends Component
{
    public $product;

    public $isOpen = false;

    protected $listeners = ['openQuickView' => 'open'];

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

    public function render()
    {
        return view('livewire.store.quick-view');
    }
}
