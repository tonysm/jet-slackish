<?php

namespace App\Events\Channels;

use App\Models\Channel as ChannelModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChannel implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ChannelModel $channel;

    public function __construct(ChannelModel $channel)
    {
        $this->channel = $channel;
    }

    public function broadcastOn()
    {
        return new PresenceChannel(
            "App.Models.Team.{$this->channel->team_id}"
        );
    }
}
