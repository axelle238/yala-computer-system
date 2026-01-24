<?php

namespace App\Livewire\Marketing\Reviews;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Moderasi Ulasan - Yala Computer')]
class Manager extends Component
{
    use WithPagination;

    public $filter = 'pending'; // pending, approved, rejected, all
    public $search = '';
    
    // Reply
    public $replyingTo = null;
    public $replyMessage = '';

    public function approve($id)
    {
        Review::where('id', $id)->update(['is_approved' => true]);
        $this->dispatch('notify', message: 'Ulasan disetujui.', type: 'success');
    }

    public function reject($id)
    {
        Review::where('id', $id)->update(['is_approved' => false]);
        $this->dispatch('notify', message: 'Ulasan ditolak/disembunyikan.');
    }

    public function delete($id)
    {
        Review::destroy($id);
        $this->dispatch('notify', message: 'Ulasan dihapus permanen.', type: 'warning');
    }

    public function startReply($id)
    {
        $this->replyingTo = $id;
        $review = Review::find($id);
        $this->replyMessage = $review->reply ?? '';
    }

    public function saveReply()
    {
        $this->validate(['replyMessage' => 'required|string']);
        
        Review::where('id', $this->replyingTo)->update([
            'reply' => $this->replyMessage,
            'replied_at' => now()
        ]);

        $this->replyingTo = null;
        $this->replyMessage = '';
        $this->dispatch('notify', message: 'Balasan terkirim.', type: 'success');
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->replyMessage = '';
    }

    public function render()
    {
        $reviews = Review::with('product')
            ->when($this->search, function($q) {
                $q->where('comment', 'like', '%' . $this->search . '%')
                  ->orWhere('reviewer_name', 'like', '%' . $this->search . '%');
            })
            ->when($this->filter !== 'all', function($q) {
                if ($this->filter === 'pending') {
                    // Logic: approved is boolean. Pending usually means not explicitly processed if we had a status column.
                    // But here we rely on is_approved. Let's assume pending = false initially?
                    // Actually usually systems auto-approve or hold. 
                    // Let's assume: is_approved = false means Pending/Hidden.
                    // To distinguish Rejected vs Pending, we might need a status column or timestamp.
                    // For simplicity: Pending = is_approved false (default state if manual approval needed)
                    // If we want 'Rejected', we might need to delete or flag. 
                    // Let's stick to simple boolean: Approved (Visible) vs Pending/Hidden (Not Visible).
                    $q->where('is_approved', $this->filter === 'approved');
                } else {
                    $q->where('is_approved', $this->filter === 'approved');
                }
            })
            ->latest()
            ->paginate(10);

        return view('livewire.marketing.reviews.manager', [
            'reviews' => $reviews
        ]);
    }
}