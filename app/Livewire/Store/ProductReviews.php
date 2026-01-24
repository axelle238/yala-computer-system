<?php

namespace App\Livewire\Store;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class ProductReviews extends Component
{
    use WithPagination, WithFileUploads;

    public $productId;
    public $rating = 5;
    public $comment = '';
    public $photos = [];
    
    // State
    public $canReview = false;
    public $hasReviewed = false;
    public $qualifyingOrderId = null;

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

        // 2. Cek order yang valid
        $order = Order::where('user_id', $userId)
            ->whereIn('status', ['received', 'completed'])
            ->whereHas('items', function ($q) {
                $q->where('product_id', $this->productId);
            })
            ->latest()
            ->first();

        if ($order) {
            $this->canReview = true;
            $this->qualifyingOrderId = $order->id;
        } else {
            $this->canReview = false;
        }
    }

    public function submitReview()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:500',
            'photos.*' => 'image|max:2048', 
        ]);

        if (!$this->canReview || !$this->qualifyingOrderId) {
            $this->dispatch('notify', message: 'Anda tidak memenuhi syarat untuk mereview produk ini.', type: 'error');
            return;
        }

        $imagePaths = [];
        foreach ($this->photos as $photo) {
            $imagePaths[] = $photo->store('reviews', 'public');
        }

        Review::create([
            'product_id' => $this->productId,
            'user_id' => Auth::id(),
            'order_id' => $this->qualifyingOrderId,
            'reviewer_name' => Auth::user()->name,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'images' => $imagePaths, 
            'is_approved' => false, // Auto-moderation needed usually, setting false for admin approval
        ]);

        $this->dispatch('notify', message: 'Terima kasih! Ulasan Anda menunggu persetujuan admin.', type: 'success');
        
        $this->reset(['comment', 'rating', 'photos']);
        $this->checkReviewEligibility();
    }

    public function render()
    {
        $reviews = Review::where('product_id', $this->productId)
            ->where('is_approved', true)
            ->latest()
            ->paginate(5);

        $stats = [
            'count' => Review::where('product_id', $this->productId)->where('is_approved', true)->count(),
            'avg' => Review::where('product_id', $this->productId)->where('is_approved', true)->avg('rating') ?? 0,
            '5_star' => Review::where('product_id', $this->productId)->where('is_approved', true)->where('rating', 5)->count(),
            '4_star' => Review::where('product_id', $this->productId)->where('is_approved', true)->where('rating', 4)->count(),
            '3_star' => Review::where('product_id', $this->productId)->where('is_approved', true)->where('rating', 3)->count(),
            '2_star' => Review::where('product_id', $this->productId)->where('is_approved', true)->where('rating', 2)->count(),
            '1_star' => Review::where('product_id', $this->productId)->where('is_approved', true)->where('rating', 1)->count(),
        ];

        return view('livewire.store.product-reviews', [
            'reviews' => $reviews,
            'stats' => $stats
        ]);
    }
}