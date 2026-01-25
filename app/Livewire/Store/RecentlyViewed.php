<?php

namespace App\Livewire\Store;

use App\Models\Product;
use Livewire\Component;

class RecentlyViewed extends Component
{
    public $products = [];

    public function mount()
    {
        $viewedIds = session()->get('recently_viewed', []);

        if (! empty($viewedIds)) {
            $this->products = Product::whereIn('id', $viewedIds)
                ->where('is_active', true)
                ->take(6)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.store.recently-viewed');
    }
}
