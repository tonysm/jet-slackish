<?php

namespace App\Actions\Channels;

use App\Events\Channels\NewMessage;
use App\Models\Channel;
use App\Models\Message;
use App\Models\User;

class CreateNewMessage
{
    public function handle(User $user, Channel $channel, string $message): Message
    {
        if (! $user->belongsToTeam($channel->team)) {
            abort(403);
        }

        $channel->messages()->save($message = new Message([
            'user_id' => $user->id,
            'content' => $message,
        ]));

        broadcast(new NewMessage($message->load(['channel', 'user'])))->toOthers();

        return $message;
    }
}
