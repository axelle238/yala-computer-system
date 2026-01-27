<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Cetak Label Produk')]
class LabelMaker extends Component
{
    public $search = '';

    public $searchResults = [];

    public $selectedProducts = []; // [[id, name, price, qty]]

    // Print Settings
    public $paperSize = 'a4'; // a4, thermal

    public $showPrice = true;

    public $showBarcode = true;

    public function updatedSearch()
    {
        if (strlen($this->search) > 2) {
            $this->searchResults = Product::where('name', 'like', '%'.$this->search.'%')
                ->orWhere('sku', 'like', '%'.$this->search.'%')
                ->take(5)->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function addProduct($id)
    {
        $product = Product::find($id);
        if ($product) {
            $this->selectedProducts[] = [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->sell_price,
                'barcode' => $product->barcode ?? $product->sku,
                'qty' => 1,
            ];
        }
        $this->search = '';
        $this->searchResults = [];
    }

    public function removeProduct($index)
    {
        unset($this->selectedProducts[$index]);
        $this->selectedProducts = array_values($this->selectedProducts);
    }

    public function printLabels()
    {
        if (empty($this->selectedProducts)) {
            $this->dispatch('notify', message: 'Pilih produk terlebih dahulu.', type: 'error');

            return;
        }

        // Pass data via Session to a clean print route (simple approach without PDF lib dependency issues)
        session()->put('print_labels', [
            'items' => $this->selectedProducts,
            'settings' => [
                'size' => $this->paperSize,
                'price' => $this->showPrice,
                'barcode' => $this->showBarcode,
            ],
        ]);

        return redirect()->route('admin.cetak.label-masal');
    }

    public function render()
    {
        return view('livewire.products.label-maker');
    }
}
