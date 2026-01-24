<?php

namespace App\Livewire\Store;

use App\Models\ProductAlert;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductAlert extends Component
{
    public $productId;
    public $email = '';
    public $isSubscribed = false;

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->checkSubscription();
    }

    public function checkSubscription()
    {
        if (Auth::check()) {
            $this->isSubscribed = ProductAlert::where('product_id', $this->productId)
                ->where('user_id', Auth::id())
                ->where('is_notified', false)
                ->exists();
        } else {
            // Guest check not possible without email input first, handled in view state
        }
    }

    public function subscribe()
    {
        if (!Auth::check()) {
            $this->validate(['email' => 'required|email']);
        }

        ProductAlert::updateOrCreate(
            [
                'product_id' => $this->productId,
                'user_id' => Auth::id(),
                'email' => Auth::check() ? Auth::user()->email : $this->email,
            ],
            [
                'is_notified' => false
            ]
        );

        $this->isSubscribed = true;
        $this->dispatch('notify', message: 'Anda akan diberitahu saat stok tersedia!', type: 'success');
    }

    public function render()
    {
        return view('livewire.store.product-alert');
    }
}