<?php

namespace App\Livewire\Store;

use App\Models\ProductDiscussion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProductDiscussions extends Component
{
    use WithPagination;

    public $productId;
    public $message;
    public $replyToId = null;
    public $replyMessage;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function ask()
    {
        $this->validate([
            'message' => 'required|string|min:5|max:500',
        ]);

        if (!Auth::check()) {
            $this->dispatch('notify', message: 'Silakan login untuk bertanya.', type: 'error');
            return;
        }

        ProductDiscussion::create([
            'product_id' => $this->productId,
            'user_id' => Auth::id(),
            'message' => $this->message,
        ]);

        $this->message = '';
        $this->dispatch('notify', message: 'Pertanyaan Anda berhasil dikirim.', type: 'success');
    }

    public function setReplyTo($id)
    {
        $this->replyToId = $id;
    }

    public function cancelReply()
    {
        $this->replyToId = null;
        $this->replyMessage = '';
    }

    public function reply($parentId)
    {
        $this->validate([
            'replyMessage' => 'required|string|min:2|max:500',
        ]);

        if (!Auth::check()) {
            $this->dispatch('notify', message: 'Silakan login untuk membalas.', type: 'error');
            return;
        }

        ProductDiscussion::create([
            'product_id' => $this->productId,
            'user_id' => Auth::id(),
            'message' => $this->replyMessage,
            'parent_id' => $parentId,
            'is_admin_reply' => Auth::user()->isAdmin() || Auth::user()->isOwner(),
        ]);

        $this->replyToId = null;
        $this->replyMessage = '';
        $this->dispatch('notify', message: 'Balasan terkirim.', type: 'success');
    }

    public function delete($id)
    {
        $discussion = ProductDiscussion::findOrFail($id);
        if (Auth::id() === $discussion->user_id || Auth::user()->isAdmin()) {
            $discussion->delete();
            $this->dispatch('notify', message: 'Diskusi dihapus.');
        }
    }

    public function render()
    {
        $discussions = ProductDiscussion::with(['user', 'replies.user'])
            ->where('product_id', $this->productId)
            ->whereNull('parent_id')
            ->latest()
            ->paginate(5);

        return view('livewire.store.product-discussions', [
            'discussions' => $discussions
        ]);
    }
}
