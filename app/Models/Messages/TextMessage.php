<?php

namespace App\Models\Messages;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function message()
    {
        return $this->morphOne(Message::class, 'content');
    }
}
