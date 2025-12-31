<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function providers()
    {
        return $this->hasMany(UserProvider::class);
    }

    public function provider(string $provider)
    {
        return $this->providers()->where('provider', $provider)->first();
    }

    public function getAvatarAttribute()
    {
        if ($this->avatar_url && str_starts_with($this->avatar_url, 'avatars/')) {
            return asset('storage/' . $this->avatar_url);
        }

        return $this->avatar_url ?: asset('images/avatar.png');
    }
}
