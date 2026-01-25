<?php

namespace App\Livewire\Store;

use App\Models\ProductDiscussion; // Asumsi model ini ada
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProductDiscussions extends Component
{
    use WithPagination;

    public $productId;

    public $message = '';

    public $replyToId = null;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function setReply($id)
    {
        $this->replyToId = $id;
    }

    public function cancelReply()
    {
        $this->replyToId = null;
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string|min:3',
        ]);

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        ProductDiscussion::create([
            'user_id' => Auth::id(),
            'product_id' => $this->productId,
            'message' => $this->message,
            'parent_id' => $this->replyToId,
        ]);

        $this->reset(['message', 'replyToId']);
        $this->dispatch('notify', message: 'Pesan terkirim.', type: 'success');
    }

    public function render()
    {
        $discussions = ProductDiscussion::with(['user', 'replies.user'])
            ->where('product_id', $this->productId)
            ->whereNull('parent_id')
            ->latest()
            ->paginate(5);

        return view('livewire.store.product-discussions', ['discussions' => $discussions]);
    }
}
