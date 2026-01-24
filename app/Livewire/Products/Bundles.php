<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\ProductBundle;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Kelola Paket Produk (Bundling) - Yala Computer')]
class Bundles extends Component
{
    public $search = '';
    public $selectedParentId = null;
    public $childSearch = '';
    
    // Form
    public $bundleItems = []; // [['child_id', 'name', 'qty']]

    public function selectParent($id)
    {
        $this->selectedParentId = $id;
        $this->loadBundleItems();
    }

    public function loadBundleItems()
    {
        $this->bundleItems = [];
        $bundles = ProductBundle::with('childProduct')->where('parent_product_id', $this->selectedParentId)->get();
        foreach ($bundles as $b) {
            $this->bundleItems[] = [
                'child_id' => $b->child_product_id,
                'name' => $b->childProduct->name,
                'qty' => $b->quantity
            ];
        }
    }

    public function addChild($id)
    {
        $product = Product::find($id);
        if (!$product) return;

        // Check duplicate
        foreach ($this->bundleItems as $item) {
            if ($item['child_id'] == $id) return;
        }

        $this->bundleItems[] = [
            'child_id' => $product->id,
            'name' => $product->name,
            'qty' => 1
        ];
        
        $this->childSearch = '';
    }

    public function removeChild($index)
    {
        unset($this->bundleItems[$index]);
        $this->bundleItems = array_values($this->bundleItems);
    }

    public function updateQty($index, $qty)
    {
        $this->bundleItems[$index]['qty'] = max(1, intval($qty));
    }

    public function save()
    {
        if (!$this->selectedParentId) return;

        $parent = Product::find($this->selectedParentId);
        
        // Update Flag
        $parent->update(['is_bundle' => count($this->bundleItems) > 0]);

        // Sync Relations
        ProductBundle::where('parent_product_id', $this->selectedParentId)->delete();

        foreach ($this->bundleItems as $item) {
            ProductBundle::create([
                'parent_product_id' => $this->selectedParentId,
                'child_product_id' => $item['child_id'],
                'quantity' => $item['qty']
            ]);
        }

        $this->dispatch('notify', message: 'Konfigurasi Paket Berhasil Disimpan!', type: 'success');
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(10);

        $childProducts = [];
        if (strlen($this->childSearch) > 2) {
            $childProducts = Product::where('name', 'like', '%' . $this->childSearch . '%')
                ->where('id', '!=', $this->selectedParentId) // Prevent recursive bundle
                ->take(5)->get();
        }

        return view('livewire.products.bundles', [
            'products' => $products,
            'childCandidates' => $childProducts
        ]);
    }
}
