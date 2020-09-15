<?php

namespace App\Actions\Channels;

use App\Events\Channels\NewMessage;
use App\Models\Channel;
use App\Models\Message;
use App\Models\Messages\TextMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateNewMessage
{
    public function handle(User $user, Channel $channel, string $message): Message
    {
        if (! $user->belongsToTeam($channel->team)) {
            abort(403);
        }

        /** @var \App\Models\Message $message */
        $message = DB::transaction(function () use ($channel, $user, $message) {
            /** @var \App\Models\Messages\TextMessage $messageContent */
            $messageContent = TextMessage::create(['content' => $message]);

            $channel->messages()->save($message = new Message([
                'user_id' => $user->id,
                'content_type' => $messageContent->getMorphClass(),
                'content_id' => $messageContent->id,
            ]));

            return $message;
        });

        broadcast(new NewMessage($message->load(['channel', 'user'])->loadMorph('content', [
            TextMessage::class => [],
        ])))->toOthers();

        return $message;
    }
}
