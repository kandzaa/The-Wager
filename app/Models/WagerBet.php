<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WagerBet extends Model
{
    protected $fillable = [
        'wager_id',
        'wager_player_id',
        'wager_choice_id',
        'bet_amount',
        'status',
        'actual_payout',
    ];

    protected $casts = [
        'bet_amount'    => 'decimal:2',
        'actual_payout' => 'decimal:2',
    ];

    /**
     * Get the wager this bet belongs to.
     */
    public function wager()
    {
        return $this->belongsTo(Wager::class);
    }

    /**
     * Get the wager player (user who placed the bet).
     */
    public function wagerPlayer()
    {
        return $this->belongsTo(WagerPlayer::class);
    }

    /**
     * Get the choice that was bet on.
     */
    public function wagerChoice()
    {
        return $this->belongsTo(WagerChoice::class);
    }

    /**
     * Scope to get only winning bets.
     */
    public function scopeWon($query)
    {
        return $query->where('status', 'won');
    }

    /**
     * Scope to get only losing bets.
     */
    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }

    /**
     * Scope to get pending bets.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
