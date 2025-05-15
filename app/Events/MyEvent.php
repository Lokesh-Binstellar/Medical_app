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

  public $message;

  public function __construct($message)
  {
      $this->message = $message;
  }
  /**
     * The channel the event should broadcast on.
     *
     * @return Channel
     */
  public function broadcastOn()
  {
      return new Channel('my-channel');
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
    // public function broadcastWith(): array
    // {
    //     return $this->message;
    // }
}
