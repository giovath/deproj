<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProvider extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'nickname',
        'avatar_url',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'token_expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
