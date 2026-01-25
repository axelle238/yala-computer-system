<?php

namespace App\Livewire\Store;

use App\Models\Order;
use App\Models\Review; // Asumsi model Review ada
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProductReviews extends Component
{
    use WithPagination;

    public $productId;
    public $rating = 5;
    public $comment = '';
    public $canReview = false;

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->checkReviewPermission();
    }

    public function checkReviewPermission()
    {
        if (Auth::check()) {
            // User can review if they bought the item and haven't reviewed it yet
            $hasBought = Order::where('user_id', Auth::id())
                ->where('status', 'completed')
                ->whereHas('items', function ($q) {
                    $q->where('product_id', $this->productId);
                })->exists();
            
            $alreadyReviewed = Review::where('user_id', Auth::id())
                ->where('product_id', $this->productId)
                ->exists();

            $this->canReview = $hasBought && !$alreadyReviewed;
        }
    }

    public function submitReview()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
        ]);

        if (!$this->canReview) return;

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $this->productId,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'is_approved' => true, // Auto approve for now
        ]);

        $this->reset(['rating', 'comment']);
        $this->canReview = false;
        $this->dispatch('notify', message: 'Ulasan berhasil dikirim!', type: 'success');
    }

    public function render()
    {
        $reviews = Review::with('user')
            ->where('product_id', $this->productId)
            ->where('is_approved', true)
            ->latest()
            ->paginate(5);

        return view('livewire.store.product-reviews', ['reviews' => $reviews]);
    }
}
