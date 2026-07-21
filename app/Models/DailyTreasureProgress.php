<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTreasureProgress extends Model
{

    protected $fillable = [

        'captain_id',

        'mission1_completed',

        'mission2_completed',

        'treasure_available',

        'treasure_collected',

        'expires_at',

    ];


    protected $casts = [

        'mission1_completed' => 'boolean',

        'mission2_completed' => 'boolean',

        'treasure_available' => 'boolean',

        'treasure_collected' => 'boolean',

        'expires_at' => 'datetime',

    ];


    public function captain()
    {
        return $this->belongsTo(
            Captain::class
        );
    }
}
