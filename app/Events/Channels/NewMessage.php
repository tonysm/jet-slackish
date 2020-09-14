<?php

namespace App\Events\Channels;

use App\Models\Message;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage
{
    use Dispatchable, SerializesModels;

    public Message $message;

    public function __construct(Message  $message)
    {
        $this->message = $message;
    }
}
