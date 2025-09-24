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
        'players',
        'game_history',
        'starting_time'
    ];

    protected $casts = [
        'ending_time' => 'datetime',
        'starting_time' => 'datetime',
        'pot' => 'integer',
        'players' => 'array',
        'game_history' => 'array'
    ];
    
    protected $attributes = [
        'players' => '[]',
        'game_history' => '[]',
        'pot' => 0,
        'status' => 'public'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function choices()
    {
        return $this->hasMany(WagerChoice::class)->orderBy('sort_order');
    }

    public function isActive()
    {
        return $this->ending_time > now();
    }

    public function isFull()
    {
        return count($this->players ?? []) >= $this->max_players;
    }

    /**
     * Remove a player from the wager
     */
    public function removePlayer($userId)
    {
        $players = collect($this->players)->filter(function ($player) use ($userId) {
            return ! isset($player['user_id']) || $player['user_id'] != $userId;
        })->values()->toArray();

        $this->update(['players' => $players]);
        return true;
    }

    /**
     * Get player count
     */
    public function getPlayerCountAttribute()
    {
        return count($this->players);
    }

    /**
     * Get available spots
     */
    public function getAvailableSpotsAttribute()
    {
        return $this->max_players - $this->player_count;
    }
}
