<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

   public function broadcastOn(): array
{
    // Menggunakan Channel biasa (Public) agar Server 2 langsung bisa dengar tanpa dicekal auth
    return [
        new \Illuminate\Broadcasting\Channel('chat.' . $this->message->room_id),
    ];
}
}