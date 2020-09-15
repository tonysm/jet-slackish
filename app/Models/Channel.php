<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property \App\Models\Team $team
 * @property int $team_id
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $messages
 */
class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'team_id' => 'int',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }
}
