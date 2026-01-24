<?php

namespace App\Livewire\Store;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProductReviews extends Component
{
    use WithPagination;

    public $productId;
    public $rating = 5;
    public $comment = '';
    
    // State
    public $canReview = false;
    public $hasReviewed = false;

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->checkReviewEligibility();
    }

    public function checkReviewEligibility()
    {
        if (!Auth::check()) {
            $this->canReview = false;
            return;
        }

        $userId = Auth::id();

        // 1. Cek apakah sudah pernah review
        $this->hasReviewed = Review::where('product_id', $this->productId)
            ->where('user_id', $userId)
            ->exists();

        if ($this->hasReviewed) {
            $this->canReview = false;
            return;
        }

        // 2. Cek apakah pernah beli dan status completed
        $hasBought = Order::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereHas('items', function ($q) {
                $q->where('product_id', $this->productId);
            })
            ->exists();

        $this->canReview = $hasBought;
    }

    public function submitReview()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:500',
        ]);

        if (!$this->canReview) {
            $this->dispatch('notify', message: 'Anda tidak memenuhi syarat untuk mereview produk ini.', type: 'error');
            return;
        }

        Review::create([
            'product_id' => $this->productId,
            'user_id' => Auth::id(),
            'reviewer_name' => Auth::user()->name,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'is_approved' => true, // Auto-approve, bisa diubah logicnya nanti
        ]);

        $this->dispatch('notify', message: 'Terima kasih! Ulasan Anda telah diterbitkan.', type: 'success');
        
        // Reset & Re-check
        $this->comment = '';
        $this->rating = 5;
        $this->checkReviewEligibility();
    }

    public function render()
    {
        $reviews = Review::where('product_id', $this->productId)
            ->where('is_approved', true)
            ->latest()
            ->paginate(5);

        $stats = [
            'count' => Review::where('product_id', $this->productId)->count(),
            'avg' => Review::where('product_id', $this->productId)->avg('rating') ?? 0,
            '5_star' => Review::where('product_id', $this->productId)->where('rating', 5)->count(),
            '4_star' => Review::where('product_id', $this->productId)->where('rating', 4)->count(),
            '3_star' => Review::where('product_id', $this->productId)->where('rating', 3)->count(),
            '2_star' => Review::where('product_id', $this->productId)->where('rating', 2)->count(),
            '1_star' => Review::where('product_id', $this->productId)->where('rating', 1)->count(),
        ];

        return view('livewire.store.product-reviews', [
            'reviews' => $reviews,
            'stats' => $stats
        ]);
    }
}
