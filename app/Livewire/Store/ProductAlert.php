<?php

namespace App\Livewire\Store;

use App\Models\ProductAlert as ProductAlertModel;
use Livewire\Component;

class ProductAlert extends Component
{
    public $productId;
    public $email;
    public $showModal = false;

    protected $rules = [
        'email' => 'required|email',
    ];

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function notifyMe()
    {
        $this->validate();

        ProductAlertModel::create([
            'product_id' => $this->productId,
            'email' => $this->email,
        ]);

        $this->showModal = false;
        $this->dispatch('notify', message: 'Anda akan diberitahu saat produk tersedia!', type: 'success');
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.store.product-alert');
    }
}
