<?php

namespace App\Livewire\Marketing\Reviews;

use App\Models\Review;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Review Manager - Yala Computer')]
class Manager extends Component
{
    use WithPagination;

    public function toggleApproval($id)
    {
        $review = Review::find($id);
        $review->update(['is_approved' => ! $review->is_approved]);
        $this->dispatch('notify', message: 'Status review updated.', type: 'success');
    }

    public function render()
    {
        $reviews = Review::with(['user', 'product'])->latest()->paginate(10);

        return view('livewire.marketing.reviews.manager', ['reviews' => $reviews]);
    }
}
