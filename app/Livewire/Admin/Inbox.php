<?php

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Kotak Masuk & Pesan')]
class Inbox extends Component
{
    use WithPagination;

    public $selectedMessage = null;
    public $replyBody = '';
    public $filter = 'all'; // all, unread

    public function selectMessage($id)
    {
        $this->selectedMessage = ContactMessage::findOrFail($id);
        
        // Mark as read logic (if column exists, else ignore)
        // $this->selectedMessage->update(['is_read' => true]); 
    }

    public function sendReply()
    {
        $this->validate(['replyBody' => 'required|min:10']);

        // In real app: Mail::to($this->selectedMessage->email)->send(new ReplyMail($this->replyBody));
        
        // Simulate sending
        sleep(1);
        
        $this->dispatch('notify', message: 'Balasan terkirim ke ' . $this->selectedMessage->email, type: 'success');
        $this->replyBody = '';
        $this->selectedMessage = null;
    }

    public function delete($id)
    {
        ContactMessage::destroy($id);
        if ($this->selectedMessage && $this->selectedMessage->id == $id) {
            $this->selectedMessage = null;
        }
        $this->dispatch('notify', message: 'Pesan dihapus.', type: 'success');
    }

    public function render()
    {
        $messages = ContactMessage::latest()->paginate(10);

        return view('livewire.admin.inbox', [
            'messages' => $messages
        ]);
    }
}
