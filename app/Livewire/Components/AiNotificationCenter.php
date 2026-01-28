<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class AiNotificationCenter extends Component
{
    public $notifications = [];

    #[On('notify')]
    public function addNotification($message, $type = 'info')
    {
        $id = uniqid();
        $icon = match($type) {
            'success' => 'check-circle',
            'error' => 'x-circle',
            'warning' => 'exclamation-triangle',
            'ai' => 'sparkles', // Ikon khusus AI
            default => 'information-circle'
        };

        $color = match($type) {
            'success' => 'emerald',
            'error' => 'rose',
            'warning' => 'amber',
            'ai' => 'indigo',
            default => 'blue'
        };

        $this->notifications[] = [
            'id' => $id,
            'message' => $message,
            'type' => $type,
            'icon' => $icon,
            'color' => $color,
            'timestamp' => now()->format('H:i')
        ];

        // Auto remove after 5 seconds (except warnings)
        if ($type !== 'warning') {
            $this->dispatch('remove-notification-later', id: $id);
        }
    }

    public function remove($id)
    {
        $this->notifications = array_filter($this->notifications, fn($n) => $n['id'] !== $id);
    }

    public function render()
    {
        return view('livewire.components.ai-notification-center');
    }
}
