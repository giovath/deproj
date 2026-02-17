<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GameMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'status',
        'slot_1_user_id',
        'slot_2_user_id',
        'ready_slot_1',
        'ready_slot_2',
        'invite_code',
        'room_id',
        'winner_id',
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

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
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

        $this->save();
    }


    public function markReady(int $userId): void
    {
        if ($this->slot_1_user_id === $userId) {
            $this->ready_slot_1 = true;
        }

        if ($this->slot_2_user_id === $userId) {
            $this->ready_slot_2 = true;
        }

        if ($this->ready_slot_1 && $this->ready_slot_2) {
            $this->status = 'ready';
        }

        $this->save();
    }

    public function bothReady(): bool
    {
        return $this->ready_slot_1 && $this->ready_slot_2;
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
