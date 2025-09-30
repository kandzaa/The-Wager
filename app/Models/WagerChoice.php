<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WagerChoice extends Model
{
    protected $fillable = [
        'wager_id',
        'label',
        'total_bet',
    ];

    protected $casts = [
        'total_bet' => 'decimal:2',
    ];

    protected $attributes = [
        'total_bet' => 0,
    ];

    public function wager()
    {
        return $this->belongsTo(Wager::class);
    }

    public function bets()
    {
        return $this->hasMany(WagerBet::class, 'wager_choice_id');
    }
}
