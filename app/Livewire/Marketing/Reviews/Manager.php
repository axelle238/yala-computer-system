<?php

namespace App\Livewire\Marketing\Reviews;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Ulasan & Reputasi - Yala Computer')]
class Manager extends Component
{
    use WithPagination;

    public $filterRating = 'all'; // all, 5, 4, 3, 2, 1
    public $search = '';
    
    // Reply Modal
    public $replyingToId = null;
    public $replyContent = '';
    public $isModalOpen = false;

    public function updatedFilterRating() { $this->resetPage(); }

    public function openReply($id)
    {
        $review = Review::findOrFail($id);
        $this->replyingToId = $id;
        $this->replyContent = $review->admin_reply;
        $this->isModalOpen = true;
    }

    public function saveReply()
    {
        $this->validate(['replyContent' => 'required|string|max:1000']);
        
        $review = Review::findOrFail($this->replyingToId);
        $review->update([
            'admin_reply' => $this->replyContent,
            'replied_at' => now()
        ]);

        $this->isModalOpen = false;
        $this->dispatch('notify', message: 'Balasan ulasan disimpan.', type: 'success');
    }

    public function toggleVisibility($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_hidden' => !$review->is_hidden]);
        $this->dispatch('notify', message: $review->is_hidden ? 'Ulasan disembunyikan.' : 'Ulasan ditampilkan kembali.', type: 'success');
    }

    public function render()
    {
        $reviews = Review::with(['user', 'product'])
            ->when($this->filterRating !== 'all', fn($q) => $q->where('rating', $this->filterRating))
            ->when($this->search, function($q) {
                $q->whereHas('product', fn($p) => $p->where('name', 'like', '%'.$this->search.'%'))
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%'.$this->search.'%'))
                  ->orWhere('comment', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.marketing.reviews.manager', [
            'reviews' => $reviews
        ]);
    }
}
