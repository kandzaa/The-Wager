<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WagerPlayer extends Model
{
    public $timestamps = true;
    
    protected $casts = [
        'bet_amount' => 'decimal:2',
        'potential_payout' => 'decimal:2',
        'actual_payout' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'wager_id',
        'user_id',
        'bet_amount',
        'choice_id',
        'status',
        'potential_payout',
        'actual_payout',
    ];

    public function wager(): BelongsTo
    {
        return $this->belongsTo(Wager::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function choice(): BelongsTo
    {
        return $this->belongsTo(WagerChoice::class, 'choice_id');
    }

    public function bets(): HasMany
    {
        return $this->hasMany(WagerBet::class);
    }
    
    public function getAmountAttribute()
    {
        return $this->bet_amount;
    }

    public function getTotalBetAmountAttribute(): float
    {
        return $this->bets->sum('bet_amount');
    }
}
