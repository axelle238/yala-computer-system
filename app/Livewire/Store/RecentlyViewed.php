<?php

namespace App\Livewire\Store;

use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class RecentlyViewed extends Component
{
    public function render()
    {
        $ids = Session::get('recently_viewed', []);
        $products = Product::whereIn('id', $ids)->take(6)->get();

        if ($products->isEmpty()) {
            return '<div></div>';
        }

        return view('livewire.store.recently-viewed', [
            'products' => $products
        ]);
    }
}
