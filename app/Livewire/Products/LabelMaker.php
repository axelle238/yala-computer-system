<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
#[Title('Cetak Label Barcode')]
class LabelMaker extends Component
{
    // Search
    public $search = '';
    public $searchResults = [];

    // Selected Items
    public $items = []; // [product_id => qty]

    // Print Config
    public $paperSize = 'a4'; // a4, thermal_80mm
    public $showPrice = true;
    public $showName = true;

    public function updatedSearch()
    {
        if (strlen($this->search) > 2) {
            $this->searchResults = Product::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('sku', 'like', '%' . $this->search . '%')
                ->limit(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function addItem($id)
    {
        if (!isset($this->items[$id])) {
            $this->items[$id] = 1; // Default 1 label
        }
        $this->search = '';
        $this->searchResults = [];
    }

    public function removeItem($id)
    {
        unset($this->items[$id]);
    }

    public function updateQty($id, $qty)
    {
        if ($qty > 0) {
            $this->items[$id] = $qty;
        } else {
            $this->removeItem($id);
        }
    }

    public function render()
    {
        $products = Product::whereIn('id', array_keys($this->items))->get();
        
        return view('livewire.products.label-maker', [
            'selectedProducts' => $products
        ]);
    }
}
