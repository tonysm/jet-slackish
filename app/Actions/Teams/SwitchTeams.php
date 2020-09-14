<?php

namespace App\Actions\Teams;

use App\Models\Team;
use App\Models\User;

class SwitchTeams
{
    public function handle(User $user, Team $team): void
    {
        if (! $user->belongsToTeam($team)) {
            abort(403);
        }

        $user->forceFill([
            'current_team_id' => $team->id,
        ])->save();
    }
}
