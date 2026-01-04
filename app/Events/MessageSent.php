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

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        if (!empty($this->message->room_key)) {
            return [
                new PrivateChannel('room.' . $this->message->room_key),
            ];
        }

        // Broadcast to private channels for both sender and receiver
        return [
            new PrivateChannel('chat.' . $this->message->sender_id),
            new PrivateChannel('chat.' . $this->message->receiver_id),
        ];
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
     */
    public function broadcastWith(): array
    {
        $sender = $this->message->sender;
        $senderAvatar = null;
        if ($sender && $sender->profile_image) {
            $senderAvatar = \Illuminate\Support\Facades\Storage::url($sender->profile_image);
        }

        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'room_key' => $this->message->room_key,
            'sender_name' => $sender?->name,
            'sender_avatar' => $senderAvatar,
            'message' => $this->message->message,
            'file_path' => $this->message->file_path ? \Illuminate\Support\Facades\Storage::url($this->message->file_path) : null,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}
