<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wager extends Model
{
    protected $fillable = [
        'name',
        'description',
        'creator_id',
        'max_players',
        'ending_time',
        'status',
        'pot',
        'starting_time',
        'winning_choice_id',
    ];

    protected $casts = [
        'ending_time'   => 'datetime',
        'starting_time' => 'datetime',
        'pot'           => 'integer',
    ];

    protected $attributes = [
        'pot'    => 0,
        'status' => 'public',
    ];

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'creator_id');
    }

    public function choices()
    {
        return $this->hasMany(\App\Models\WagerChoice::class);
    }

    public function winningChoice()
    {
        return $this->belongsTo(\App\Models\WagerChoice::class, 'winning_choice_id');
    }

    public function players()
    {
        return $this->hasMany(WagerPlayer::class);
    }

    public function bets()
    {
        // Simple hasMany relationship for now
        return $this->hasMany(WagerBet::class);
    }

    public function getPlayerCountAttribute()
    {
        return $this->players()->count();
    }

    public function getTotalBetAmountAttribute()
    {
        return $this->bets()->sum('bet_amount');
    }

    public function isActive()
    {
        return $this->ending_time > now();
    }

    public function isFull()
    {
        return $this->players()->count() >= $this->max_players;
    }

    public function removePlayer($userId)
    {
        return $this->players()->where('user_id', $userId)->delete();
    }

    public function hasPlayer($userId)
    {
        return $this->players->contains('user_id', $userId);
    }
}
