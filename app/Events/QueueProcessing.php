<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueProcessing implements ShouldBroadcast, ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $queue_type;
    public $queue_payload;


    public function __construct($queue_type, $queue_payload)
    {
        $this->queue_type = $queue_type;
        $this->queue_payload = $queue_payload;
    }

    public function broadcastWith()
    {
        return [
            'queue_type' => $this->queue_type,
            'queue_payload' => $this->queue_payload
        ];
    }

    public function broadcastAs()
    {
        return 'queueprocess.'.$this->queue_type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('queueprocess');
    }
}
