<?php

namespace App\Livewire\Store;

use App\Models\Supplier;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Mitra Brand & Supplier - Yala Computer')]
class Brands extends Component
{
    public function render()
    {
        $brands = Supplier::withCount('products')->get();

        return view('livewire.store.brands', [
            'brands' => $brands,
        ]);
    }
}
