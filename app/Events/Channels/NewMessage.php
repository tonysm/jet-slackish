<?php

namespace App\Events\Channels;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    public function __construct(Message  $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PresenceChannel(
            "App.Models.Team.{$this->message->channel->team_id}"
        );
    }
}
