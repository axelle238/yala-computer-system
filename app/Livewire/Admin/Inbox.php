<?php

namespace App\Livewire\Admin;

use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
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
        
        if ($this->selectedMessage->status === 'new') {
            $this->selectedMessage->update(['status' => 'read']);
        }
    }

    public function sendReply()
    {
        $this->validate([
            'replyBody' => 'required|min:10',
        ]);

        if (!$this->selectedMessage) {
            return;
        }

        try {
            Mail::to($this->selectedMessage->email)
                ->send(new ContactReplyMail($this->replyBody, $this->selectedMessage->subject));
            
            $this->selectedMessage->update(['status' => 'replied']);
            
            $this->dispatch('notify', message: 'Balasan berhasil dikirim ke ' . $this->selectedMessage->email, type: 'success');
        } catch (\Exception $e) {
            // Log error if mail fails (e.g. invalid config) but still update status or user feedback
            // For now, we update status to indicate we TRIED to reply, or maybe we shouldn't?
            // "Operational ready" means we should tell them it failed.
            
            $this->dispatch('notify', message: 'Gagal mengirim email (Cek konfigurasi SMTP). Pesan belum ditandai terbalas.', type: 'error');
            return;
        }

        $this->replyBody = '';
        $this->selectedMessage = null;
    }

    public function delete($id)
    {
        ContactMessage::destroy($id);
        if ($this->selectedMessage && $this->selectedMessage->id == $id) {
            $this->selectedMessage = null;
        }
        $this->dispatch('notify', message: 'Pesan berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $messages = ContactMessage::latest()->paginate(10);

        return view('livewire.admin.inbox', [
            'messages' => $messages
        ]);
    }
}