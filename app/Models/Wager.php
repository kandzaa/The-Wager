<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wager extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'creator_id',
        'max_players',
        'status',
        'players',
        'game_history',
        'starting_time',
        'ending_time',
        'pot',
    ];

    protected $casts = [
        'players'       => 'array',
        'game_history'  => 'array',
        'starting_time' => 'datetime',
        'ending_time'   => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function choices()
    {
        return $this->hasMany(WagerChoice::class);
    }
}
