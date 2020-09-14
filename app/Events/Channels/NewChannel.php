<?php

namespace App\Events\Channels;

use App\Models\Channel as ChannelModel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChannel
{
    use Dispatchable, SerializesModels;

    public ChannelModel $channel;

    public function __construct(ChannelModel $channel)
    {
        $this->channel = $channel;
    }
}
