<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function wagerPlayer(): BelongsTo
    {
        return $this->belongsTo(WagerPlayer::class);
    }

    public function choice(): BelongsTo
    {
        return $this->belongsTo(WagerChoice::class, 'wager_choice_id');
    }

    public function wager(): BelongsTo
    {
        return $this->belongsTo(Wager::class);
    }
}
