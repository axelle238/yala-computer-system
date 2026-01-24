<?php

namespace App\Livewire\Store;

use App\Models\ContactMessage;
use App\Models\FaqCategory;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Pusat Bantuan - Yala Computer')]
class HelpCenter extends Component
{
    public $activeTab = ''; // Category Slug
    public $search = '';

    // Contact Form
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';

    public function mount()
    {
        $firstCat = FaqCategory::orderBy('order')->first();
        if ($firstCat) {
            $this->activeTab = $firstCat->slug;
        }
    }

    public function setTab($slug)
    {
        $this->activeTab = $slug;
    }

    public function sendMessage()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required|min:10',
        ]);

        ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        $this->reset(['name', 'email', 'subject', 'message']);
        $this->dispatch('notify', message: 'Pesan terkirim! Tim kami akan segera menghubungi Anda.', type: 'success');
    }

    public function render()
    {
        $categories = FaqCategory::orderBy('order')->get();
        
        $faqs = [];
        if ($this->activeTab) {
            $faqs = \App\Models\Faq::whereHas('category', function($q) {
                $q->where('slug', $this->activeTab);
            })
            ->when($this->search, function($q) {
                $q->where('question', 'like', '%' . $this->search . '%')
                  ->orWhere('answer', 'like', '%' . $this->search . '%');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        }

        return view('livewire.store.help-center', [
            'categories' => $categories,
            'faqs' => $faqs
        ]);
    }
}
