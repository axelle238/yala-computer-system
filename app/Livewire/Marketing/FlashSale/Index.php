<?php

namespace App\Livewire\Marketing\FlashSale;

use App\Models\FlashSale;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen Flash Sale')]
class Index extends Component
{
    use WithPagination;

    public $showForm = false;
    public $searchProduct = '';
    public $productSearchResults = [];

    // Form
    public $flashSaleId;
    public $product_id, $discount_price, $quota, $start_time, $end_time;
    public $is_active = true;
    public $selectedProductName = '';

    public function updatedSearchProduct()
    {
        if (strlen($this->searchProduct) > 2) {
            $this->productSearchResults = Product::where('name', 'like', '%' . $this->searchProduct . '%')
                ->limit(5)->get();
        } else {
            $this->productSearchResults = [];
        }
    }

    public function selectProduct($id, $name)
    {
        $this->product_id = $id;
        $this->selectedProductName = $name;
        $this->productSearchResults = [];
        $this->searchProduct = '';
    }

    public function create()
    {
        $this->reset(['flashSaleId', 'product_id', 'discount_price', 'quota', 'start_time', 'end_time', 'is_active', 'selectedProductName']);
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'discount_price' => 'required|numeric|min:1',
            'quota' => 'required|numeric|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        FlashSale::updateOrCreate(['id' => $this->flashSaleId], [
            'product_id' => $this->product_id,
            'discount_price' => $this->discount_price,
            'quota' => $this->quota,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'is_active' => $this->is_active,
        ]);

        $this->showForm = false;
        $this->dispatch('notify', message: 'Flash Sale tersimpan.', type: 'success');
    }

    public function delete($id)
    {
        FlashSale::destroy($id);
        $this->dispatch('notify', message: 'Flash Sale dihapus.', type: 'success');
    }

    public function render()
    {
        $sales = FlashSale::with('product')->latest()->paginate(10);
        return view('livewire.marketing.flash-sale.index', ['sales' => $sales]);
    }
}