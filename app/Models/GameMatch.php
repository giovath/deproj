<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GameMatch extends Model
{
    use HasFactory;

    protected $table = 'matches'; // mantém o nome da tabela

    protected $fillable = [
        'status',
        'slot_1_user_id',
        'slot_2_user_id',
        'invite_code',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relacionamentos
    |--------------------------------------------------------------------------
    */

    public function slot1User()
    {
        return $this->belongsTo(User::class, 'slot_1_user_id');
    }

    public function slot2User()
    {
        return $this->belongsTo(User::class, 'slot_2_user_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Estados
    |--------------------------------------------------------------------------
    */

    public function isWaiting(): bool
    {
        return $this->status === 'waiting';
    }

    public function isReady(): bool
    {
        return $this->status === 'ready';
    }

    /*
    |--------------------------------------------------------------------------
    | Slots
    |--------------------------------------------------------------------------
    */

    public function hasFreeSlot(): bool
    {
        return is_null($this->slot_1_user_id) || is_null($this->slot_2_user_id);
    }

    public function occupySlot(int $userId): void
    {
        if (is_null($this->slot_1_user_id)) {
            $this->slot_1_user_id = $userId;
        } elseif (is_null($this->slot_2_user_id)) {
            $this->slot_2_user_id = $userId;
        }

        if ($this->slot_1_user_id && $this->slot_2_user_id) {
            $this->status = 'ready';
        }

        $this->save();
    }

    /*
    |--------------------------------------------------------------------------
    | Invite (Fluxo C)
    |--------------------------------------------------------------------------
    */

    public function generateInviteCode(): void
    {
        if (!$this->invite_code) {
            $this->invite_code = Str::upper(Str::random(8));
            $this->save();
        }
    }
}
