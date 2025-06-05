<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel; // 👈 Import PrivateChannel
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SendMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $receiverId;

    public function __construct($message, $receiverId)
    {
        $this->message = $message;
        $this->receiverId = $receiverId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->receiverId); // 👈 private channel per user
    }

    public function broadcastAs()
    {
        return 'send-message';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message
        ];
    }
}
