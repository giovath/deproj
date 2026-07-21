<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Captain extends Model
{
    protected $fillable = [
        'user_id',
        'ref_code',
        'referral_completed'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function wallet()
    {
        return $this->hasOne(CaptainWallet::class);
    }

    public function weeklyRankings()
    {
        return $this->hasMany(
            WeeklyRanking::class
        );
    }
}
