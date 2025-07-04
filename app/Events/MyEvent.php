<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MyEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $role;
    public $userId;
    public $message;

    public function __construct($role,$userId ,$message)
    {
        $this->message = $message;
        $this->role = $role;
        $this->userId = $userId;
    }

    /**
     * The channel the event should broadcast on.
     *
     * @return Channel
     */
 public function broadcastOn()
{
    if ($this->role === 'admin') {
        // Only admin-channel for admin role
        return new Channel('admin-channel');
    }

    // For non-admin users, send to specific user channel
    if ($this->userId) {
        return new Channel('my-channel.' . $this->role . '.user.' . $this->userId);
    }

    // Fallback: role-based general channel
    return new Channel('my-channel.' . $this->role);
}


    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'my-event';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'role' => $this->role,
            'message' => $this->message,
        ];
    }
}
