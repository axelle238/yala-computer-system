<?php

namespace App\Livewire\Admin;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Pusat Pesan Pembeli - Yala Computer')]
class Inbox extends Component
{
    public $activeConversationId = null;
    public $replyMessage = '';
    public $search = '';

    public function selectConversation($id)
    {
        $this->activeConversationId = $id;
        $this->markAsRead();
    }

    public function markAsRead()
    {
        if ($this->activeConversationId) {
            Message::where('conversation_id', $this->activeConversationId)
                ->where('is_admin_reply', false)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
    }

    public function sendMessage()
    {
        $this->validate(['replyMessage' => 'required|string']);

        if (!$this->activeConversationId) return;

        Message::create([
            'conversation_id' => $this->activeConversationId,
            'user_id' => Auth::id(), // Admin ID
            'is_admin_reply' => true,
            'body' => $this->replyMessage,
            'is_read' => false
        ]);

        $this->replyMessage = '';
        $this->dispatch('message-sent');
    }

    public function render()
    {
        $conversations = Conversation::with(['customer', 'messages'])
            ->withCount(['messages as unread_count' => function($q) {
                $q->where('is_admin_reply', false)->where('is_read', false);
            }])
            ->when($this->search, function($q) {
                $q->whereHas('customer', fn($c) => $c->where('name', 'like', '%'.$this->search.'%'))
                  ->orWhere('guest_token', 'like', '%'.$this->search.'%');
            })
            ->orderByDesc('updated_at')
            ->get();

        $activeMessages = $this->activeConversationId 
            ? Message::where('conversation_id', $this->activeConversationId)->latest()->get()->reverse()
            : [];

        return view('livewire.admin.inbox', [
            'conversations' => $conversations,
            'activeMessages' => $activeMessages
        ]);
    }
}
