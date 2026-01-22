<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Form Produk - Yala Computer')]
class Form extends Component
{
    use WithFileUploads;

    public ?Product $product = null;

    // Form Properties
    public $name = '';
    public $sku = '';
    public $barcode = '';
    public $category_id = '';
    public $supplier_id = '';
    public $buy_price = 0;
    public $sell_price = 0;
    public $stock_quantity = 0;
    public $min_stock_alert = 5;
    public $description = '';
    public $image; // Temporary upload
    public $image_path; // Existing path

    public function mount($id = null)
    {
        if ($id) {
            $this->product = Product::findOrFail($id);
            $this->name = $this->product->name;
            $this->sku = $this->product->sku;
            $this->barcode = $this->product->barcode;
            $this->category_id = $this->product->category_id;
            $this->supplier_id = $this->product->supplier_id;
            $this->buy_price = $this->product->buy_price;
            $this->sell_price = $this->product->sell_price;
            $this->stock_quantity = $this->product->stock_quantity;
            $this->min_stock_alert = $this->product->min_stock_alert;
            $this->description = $this->product->description;
            $this->image_path = $this->product->image_path;
        }
    }

    public function updatedName()
    {
        // Auto generate SKU if empty (optional helper)
        if (empty($this->sku)) {
            $this->sku = strtoupper(Str::slug($this->name));
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . ($this->product->id ?? 'NULL'),
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_alert' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id ?: null,
            'buy_price' => $this->buy_price,
            'sell_price' => $this->sell_price,
            'stock_quantity' => $this->stock_quantity,
            'min_stock_alert' => $this->min_stock_alert,
            'description' => $this->description,
            'is_active' => true,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('products', 'public');
        }

        if ($this->product) {
            $this->product->update($data);
            session()->flash('success', 'Produk berhasil diperbarui!');
        } else {
            Product::create($data);
            session()->flash('success', 'Produk baru berhasil ditambahkan!');
        }

        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.products.form', [
            'categories' => Category::all(),
            'suppliers' => Supplier::all(),
        ]);
    }
}
