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
        'amount',
        'status',
        'actual_payout',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    protected $casts = [
        'bet_amount'    => 'integer', // Changed to integer
        'amount'        => 'integer', // Added to match table
        'actual_payout' => 'integer', // Changed to integer
        'status'        => 'string',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (! in_array($model->status, ['pending', 'won', 'lost'])) {
                throw new \InvalidArgumentException("Invalid status value");
            }
        });
    }

    public function wager()
    {
        return $this->belongsTo(Wager::class);
    }

    public function wagerPlayer()
    {
        return $this->belongsTo(WagerPlayer::class);
    }

    public function wagerChoice()
    {
        return $this->belongsTo(WagerChoice::class);
    }

    public function scopeWon($query)
    {
        return $query->where('status', 'won');
    }

    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
