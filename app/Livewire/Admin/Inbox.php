<?php

namespace App\Livewire\Admin;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Inbox & Pesan Pelanggan - Yala Computer')]
class Inbox extends Component
{
    public $activeConversation = null;
    public $replyMessage = '';

    public function mount()
    {
        // Placeholder: Assuming Message model handles conversations
        // If not exists, use basic implementation
    }

    public function selectConversation($userId)
    {
        $this->activeConversation = $userId;
    }

    public function sendReply()
    {
        if (!$this->activeConversation || !$this->replyMessage) return;

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->activeConversation,
            'message' => $this->replyMessage,
            'is_read' => false
        ]);

        $this->replyMessage = '';
        $this->dispatch('notify', message: 'Pesan terkirim.', type: 'success');
    }

    public function render()
    {
        // Mock data or real implementation depending on Message model availability
        // Assuming we need to create Message model first or use existing ContactMessages
        
        $conversations = \App\Models\User::whereHas('sentMessages')->with('sentMessages')->get();

        return view('livewire.admin.inbox', [
            'conversations' => $conversations
        ]);
    }
}