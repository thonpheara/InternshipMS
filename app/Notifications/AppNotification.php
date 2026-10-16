<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AppNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public ?string $actionUrl = null,
        public string $icon = 'fa-solid fa-bell',
        public string $color = 'emerald'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title'      => $this->title,
            'message'    => $this->message,
            'action_url' => $this->actionUrl,
            'icon'       => $this->icon,
            'color'      => $this->color,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id'               => $this->id,
            'title'            => $this->title,
            'message'          => $this->message,
            'action_url'       => $this->actionUrl,
            'icon'             => $this->icon,
            'color'            => $this->color,
            'created_at_human' => 'Just now',
        ]);
    }
}
