<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
// use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
// use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebRTCSignal implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $meetingId;

    public $data;

    public function __construct($meetingId, $data)
    {
        $this->meetingId = $meetingId;
        $this->data = $data;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('meeting.'.$this->meetingId);
    }

    public function broadcastAs()
    {
        return 'WebRTCSignal';
    }
}
