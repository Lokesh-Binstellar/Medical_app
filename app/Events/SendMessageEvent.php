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
     public $token;

    public function __construct($message,  $token)
    {
        $this->message = $message;
        $this->token =$token;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->token); // 👈 private channel per user
    }

    public function broadcastAs()
    {
        return 'send-message';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
             'token' => $this->token,
        ];
    }
}
