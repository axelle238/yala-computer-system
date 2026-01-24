<?php

namespace App\Livewire\Store;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\On;

class ChatWidget extends Component
{
    public $isOpen = false;
    public $conversation;
    public $newMessage = '';
    public $guestToken;

    public function mount()
    {
        $this->guestToken = Session::get('chat_guest_token');
        
        if (!$this->guestToken && !Auth::check()) {
            $this->guestToken = Str::random(32);
            Session::put('chat_guest_token', $this->guestToken);
        }

        $this->loadConversation();
    }

    public function loadConversation()
    {
        if (Auth::check()) {
            $this->conversation = Conversation::where('customer_id', Auth::id())->first();
        } else {
            $this->conversation = Conversation::where('guest_token', $this->guestToken)->first();
        }
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->markAsRead();
        }
    }

    public function sendMessage()
    {
        $this->validate(['newMessage' => 'required|string']);

        if (!$this->conversation) {
            $this->conversation = Conversation::create([
                'customer_id' => Auth::id(),
                'guest_token' => Auth::check() ? null : $this->guestToken,
                'subject' => 'New Chat',
            ]);
        }

        Message::create([
            'conversation_id' => $this->conversation->id,
            'user_id' => Auth::id(), // Null if guest
            'is_admin_reply' => false,
            'body' => $this->newMessage,
            'is_read' => false
        ]);

        $this->newMessage = '';
        $this->dispatch('message-sent'); // For scrolling to bottom
    }

    #[On('echo:chat,MessageSent')] // If using Reverb later
    public function refreshMessages()
    {
        $this->loadConversation();
        if ($this->isOpen) {
            $this->markAsRead();
        }
    }

    public function markAsRead()
    {
        if ($this->conversation) {
            $this->conversation->messages()
                ->where('is_admin_reply', true)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
    }

    public function render()
    {
        // Polling logic handled in view via wire:poll
        return view('livewire.store.chat-widget', [
            'messages' => $this->conversation ? $this->conversation->messages()->latest()->take(50)->get()->reverse() : []
        ]);
    }
}
