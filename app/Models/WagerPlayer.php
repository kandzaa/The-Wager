<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WagerPlayer extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'wager_id',
        'user_id',
    ];

    public function wager(): BelongsTo
    {
        return $this->belongsTo(Wager::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bets(): HasMany
    {
        return $this->hasMany(WagerBet::class);
    }

    public function getTotalBetAmountAttribute(): float
    {
        return $this->bets->sum('bet_amount');
    }
}
