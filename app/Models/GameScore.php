<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameScore extends Model
{
    protected $fillable = [

        'player_uuid',
        'game_code',
        'score',
        'session_id',
        'game_play_id',
        'played_at',
    ];
}
