<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\ProductBundle;
use App\Models\ProductBundleItem;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Kelola Bundle Produk - Yala Computer')]
class Bundles extends Component
{
    use WithFileUploads, WithPagination;

    public $viewMode = 'list'; // list, create, edit

    public $cari = '';

    // Form
    public $bundleId;

    public $name;

    public $description;

    public $price;

    public $image;

    public $isActive = true;

    public $bundleItems = []; // [['product_id', 'qty']]

    // Search Product for Bundle
    public $cariProduk = '';

    public $searchResults = [];

    public function updatedCariProduk()
    {
        if (strlen($this->cariProduk) > 2) {
            $this->searchResults = Product::where('name', 'like', '%'.$this->cariProduk.'%')->take(5)->get();
        }
    }

    public function addProductToBundle($id)
    {
        $product = Product::find($id);
        if ($product) {
            $this->bundleItems[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'qty' => 1,
                'price' => $product->sell_price,
            ];
        }
        $this->cariProduk = '';
        $this->searchResults = [];
    }

    public function removeProductFromBundle($index)
    {
        unset($this->bundleItems[$index]);
        $this->bundleItems = array_values($this->bundleItems);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'bundleItems' => 'required|array|min:1',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'price' => $this->price,
            'is_active' => $this->isActive,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('bundles', 'public');
        }

        if ($this->bundleId) {
            $bundle = ProductBundle::find($this->bundleId);
            $bundle->update($data);
            $bundle->item()->delete();
        } else {
            $bundle = ProductBundle::create($data);
        }

        foreach ($this->bundleItems as $item) {
            ProductBundleItem::create([
                'product_bundle_id' => $bundle->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['qty'],
            ]);
        }

        $this->dispatch('notify', message: 'Paket bundling berhasil disimpan.', type: 'success');
        $this->viewMode = 'list';
    }

    public function edit($id)
    {
        $bundle = ProductBundle::with('item.produk')->find($id);
        $this->bundleId = $bundle->id;
        $this->name = $bundle->name;
        $this->description = $bundle->description;
        $this->price = $bundle->price;
        $this->isActive = $bundle->is_active;

        $this->bundleItems = [];
        foreach ($bundle->item as $item) {
            $this->bundleItems[] = [
                'product_id' => $item->product_id,
                'name' => $item->produk->name,
                'qty' => $item->quantity,
                'price' => $item->produk->sell_price,
            ];
        }

        $this->viewMode = 'edit';
    }

    public function create()
    {
        $this->reset(['bundleId', 'name', 'description', 'price', 'image', 'bundleItems']);
        $this->viewMode = 'create';
    }

    public function render()
    {
        $bundles = ProductBundle::withCount('item')
            ->where('name', 'like', '%'.$this->cari.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.products.bundles', [
            'bundles' => $bundles,
        ]);
    }
}
