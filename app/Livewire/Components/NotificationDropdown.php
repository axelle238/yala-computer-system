<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationDropdown extends Component
{
    public $unreadCount = 0;
    public $notifications = [];

    public function getListeners()
    {
        return [
            'echo-private:App.Models.User.' . Auth::id() . ',.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated' => 'refreshNotifications',
            'refreshNotifications' => '$refresh',
        ];
    }

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (Auth::check()) {
            $this->unreadCount = Auth::user()->unreadNotifications()->count();
            $this->notifications = Auth::user()->notifications()->take(5)->get();
        }
    }

    public function markAsRead($id)
    {
        if (Auth::check()) {
            Auth::user()->notifications()->where('id', $id)->first()?->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.components.notification-dropdown');
    }
}
