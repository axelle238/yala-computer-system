<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Cetak Label Barcode - Yala Computer')]
class LabelMaker extends Component
{
    public $search = '';
    public $queue = []; // ['product_id', 'name', 'price', 'sku', 'qty_to_print']

    public function updatedSearch()
    {
        // Simple search logic
    }

    public function printLabels()
    {
        if (empty($this->queue)) return;

        $key = 'print_queue_' . Str::random(10);
        Cache::put($key, $this->queue, 60); // Simpan 1 menit

        return redirect()->route('print.labels', ['key' => $key]);
    }

    public function addProduct($id)
    {
        $product = Product::find($id);
        if (!$product) return;

        // Check exists
        foreach ($this->queue as $key => $item) {
            if ($item['product_id'] == $id) {
                $this->queue[$key]['qty_to_print']++;
                return;
            }
        }

        $this->queue[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->sell_price,
            'sku' => $product->sku,
            'barcode' => $product->barcode ?? $product->sku,
            'qty_to_print' => 1
        ];
        
        $this->search = '';
    }

    public function remove($index)
    {
        unset($this->queue[$index]);
        $this->queue = array_values($this->queue);
    }

    public function updateQty($index, $qty)
    {
        if (isset($this->queue[$index])) {
            $this->queue[$index]['qty_to_print'] = max(1, intval($qty));
        }
    }

    public function clearQueue()
    {
        $this->queue = [];
    }

    public function render()
    {
        $products = [];
        if (strlen($this->search) > 2) {
            $products = Product::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('sku', 'like', '%' . $this->search . '%')
                ->take(5)
                ->get();
        }

        return view('livewire.products.label-maker', [
            'products' => $products
        ]);
    }
}
