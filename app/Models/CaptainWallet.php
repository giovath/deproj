<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaptainWallet extends Model
{
    protected $fillable = [
        'captain_id',
        'coins',
        'participations',
        'relics',
    ];


    public function captain()
    {
        return $this->belongsTo(Captain::class);
    }
}
