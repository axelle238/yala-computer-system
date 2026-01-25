<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewChatMessage extends Notification
{
    use Queueable;

    public $message;
    public $senderName;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $senderName = 'Tamu')
    {
        $this->message = $message;
        $this->senderName = $senderName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pesan Baru dari ' . $this->senderName,
            'message' => substr($this->message, 0, 100),
            'action' => route('customers.live-chat'),
            'type' => 'chat'
        ];
    }
}