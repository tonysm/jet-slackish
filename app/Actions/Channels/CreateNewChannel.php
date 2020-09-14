<?php

namespace App\Actions\Channels;

use App\Events\Channels\NewChannel;
use App\Models\Channel;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateNewChannel
{
    public function handle(User $user, Team $team, array $channelParams): Channel
    {
        if (! $user->belongsToTeam($team)) {
            abort(403);
        }

        Validator::make($channelParams, [
            'name' => [
                'required',
                'string',
                'max:25',
                Rule::unique('channels')->where('team_id', $team->id)
            ],
        ])->validateWithBag('newChannelForm');

        $channel = $team->createChannel($channelParams['name']);

        event(new NewChannel($channel));

        return $channel;
    }
}
