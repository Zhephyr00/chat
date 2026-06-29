<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // 👈 Kita pakai ShouldBroadcastNow agar langsung dikirim tanpa antrean queue
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        // Channel kita beri nama murni string: "chat-room.{id}"
        return [
            new Channel('chat-room.' . $this->message->room_id),
        ];
    }

    public function broadcastAs(): string
    {
        // Nama event kustom murni string tanpa namespace: "MessageSent"
        return 'MessageSent';
    }
}