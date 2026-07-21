<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyRanking extends Model
{
    protected $fillable = [
        'captain_id',
        'week_key',
        'position',
        'relics',
        'reward',
    ];


    public function captain()
    {
        return $this->belongsTo(
            Captain::class
        );
    }
}
