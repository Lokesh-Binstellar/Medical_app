<?php
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class RoleMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $message;
    public $role;

    public function __construct($message, $role)
    {
        $this->message = $message;
        $this->role = $role;
    }

    public function broadcastOn()
    {
        return new Channel('role.' . $this->role);
    }

    public function broadcastAs()
    {
        return 'role-message';
    }

    public function broadcastWith()
    {
        return ['message' => $this->message];
    }
}
