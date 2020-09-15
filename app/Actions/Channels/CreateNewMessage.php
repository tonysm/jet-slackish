<?php

namespace App\Actions\Channels;

use App\Events\Channels\NewMessage;
use App\Models\Channel;
use App\Models\Message;
use App\Models\Messages\MessageContentFactory;
use App\Models\Messages\TextMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreateNewMessage
{
    public function handle(User $user, Channel $channel, array $content): Message
    {
        if (! $user->belongsToTeam($channel->team)) {
            abort(403);
        }

        Validator::make($content, [
            'content.content' => 'required|string',
            'content.type' => 'required|in:text_messages',
        ])->validate();

        /** @var \App\Models\Message $message */
        $message = DB::transaction(function () use ($channel, $user, $content) {
            $messageContent = MessageContentFactory::create($content['content']);

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
