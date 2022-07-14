<?php

namespace MobileNowGroup\SubscribeMessage\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WechatSubscribeMessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $result = [];

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $result)
    {
        $this->result = $result;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}