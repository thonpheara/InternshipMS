<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message->loadMissing(['sender', 'conversation.studentProfile', 'conversation.companyProfile']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
        ];

        $recipientUserId = $this->getRecipientUserId();
        if ($recipientUserId) {
            $channels[] = new PrivateChannel('App.Models.User.' . $recipientUserId);
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id'               => $this->message->id,
            'conversation_id'  => $this->message->conversation_id,
            'sender_id'        => $this->message->sender_id,
            'sender_name'      => $this->message->sender?->name ?? 'User',
            'body'             => $this->message->body,
            'is_read'          => (bool) $this->message->is_read,
            'created_at_time'  => $this->message->created_at->format('h:i A'),
            'created_at_human' => 'Just now',
        ];
    }

    /**
     * Get the user ID of the recipient in this conversation.
     */
    protected function getRecipientUserId(): ?int
    {
        $conversation = $this->message->conversation;
        if (!$conversation) {
            return null;
        }

        if ($this->message->sender_id === $conversation->studentProfile?->user_id) {
            return $conversation->companyProfile?->user_id;
        }

        return $conversation->studentProfile?->user_id;
    }
}
