<?php

namespace App\Livewire\Store\Navbar;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
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
        $this->dispatch('notify', message: 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function render()
    {
        $notifications = Auth::user() ? Auth::user()->unreadNotifications()->take(5)->get() : collect();
        $count = Auth::user() ? Auth::user()->unreadNotifications()->count() : 0;

        return view('livewire.store.navbar.notifications', [
            'notifications' => $notifications,
            'count' => $count
        ]);
    }
}
