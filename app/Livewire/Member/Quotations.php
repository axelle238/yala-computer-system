<?php

namespace App\Livewire\Member;

use App\Models\ProductOffer;
use App\Models\Quotation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.member')]
#[Title('Penawaran Saya - Yala Computer')]
class Quotations extends Component
{
    use WithFileUploads, WithPagination;

    public $activeTab = 'received'; // 'received' (Quotations from Store), 'sent' (Offers from Customer)

    // Form Properties for Product Offer
    public $offer_product_name;

    public $offer_brand;

    public $offer_condition = 'used_good';

    public $offer_price;

    public $offer_description;

    public $offer_image;

    public $showCreateOfferModal = false;

    public function updatedActiveTab()
    {
        $this->resetPage();
    }

    public function openCreateOfferModal()
    {
        $this->reset(['offer_product_name', 'offer_brand', 'offer_condition', 'offer_price', 'offer_description', 'offer_image']);
        $this->showCreateOfferModal = true;
    }

    public function submitOffer()
    {
        $this->validate([
            'offer_product_name' => 'required|string|max:255',
            'offer_brand' => 'nullable|string|max:100',
            'offer_condition' => 'required|in:new,used_like_new,used_good,used_fair',
            'offer_price' => 'nullable|numeric|min:0',
            'offer_description' => 'nullable|string',
            'offer_image' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $imagePath = null;
        if ($this->offer_image) {
            $imagePath = $this->offer_image->store('product-offers', 'public');
        }

        ProductOffer::create([
            'user_id' => Auth::id(),
            'product_name' => $this->offer_product_name,
            'brand' => $this->offer_brand,
            'condition' => $this->offer_condition,
            'expected_price' => $this->offer_price,
            'description' => $this->offer_description,
            'image_path' => $imagePath,
            'status' => 'pending',
        ]);

        $this->showCreateOfferModal = false;
        $this->reset(['offer_product_name', 'offer_brand', 'offer_condition', 'offer_price', 'offer_description', 'offer_image']);

        // Optional: Dispatch notification
        // $this->dispatch('notify', 'Penawaran berhasil dikirim!');
    }

    public function render()
    {
        $quotations = [];
        $offers = [];

        if ($this->activeTab == 'received') {
            $quotations = Quotation::where('user_id', Auth::id())
                ->latest()
                ->paginate(10);
        } else {
            $offers = ProductOffer::where('user_id', Auth::id())
                ->latest()
                ->paginate(10);
        }

        return view('livewire.member.quotations', [
            'quotations' => $quotations,
            'offers' => $offers,
        ]);
    }
}
