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
        'game_code',
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

    /*
|--------------------------------------------------------------------------
| Remover Jogador
|--------------------------------------------------------------------------
*/
    public function removePlayer(int $userId): void
    {
        if ($this->slot_1_user_id === $userId) {
            $this->slot_1_user_id = null;
            $this->ready_slot_1 = false;
        }

        if ($this->slot_2_user_id === $userId) {
            $this->slot_2_user_id = null;
            $this->ready_slot_2 = false;
        }

        // Se ninguém ficou no match → deletar
        if (is_null($this->slot_1_user_id) && is_null($this->slot_2_user_id)) {
            $this->delete();
            return;
        }

        // Se ainda tem alguém → volta para waiting
        $this->status = 'waiting';
        $this->game_code = null;

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

        $this->syncStatus();

        $this->save();
    }

    public function syncStatus(): void
    {
        if ($this->ready_slot_1 && $this->ready_slot_2) {
            $this->status = 'ready';
        } elseif ($this->slot_1_user_id || $this->slot_2_user_id) {
            $this->status = 'waiting';
        }
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
