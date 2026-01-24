<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminNotification extends Component
{
    public $isOpen = false;

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->dispatch('notify', message: 'Semua notifikasi ditandai sudah dibaca.', type: 'success');
    }

    public function render()
    {
        return view('livewire.components.admin-notification', [
            'notifications' => Auth::user()->unreadNotifications()->take(5)->get(),
            'unreadCount' => Auth::user()->unreadNotifications()->count()
        ]);
    }
}
