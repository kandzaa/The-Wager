<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    /**
     * Get the players attribute, ensuring it's a valid array and filtering out empty entries
     *
     * @param  mixed  $value
     * @return array
     */
    public function getPlayersAttribute($value)
    {
        $players = json_decode($value, true) ?? [];
        
        // Filter out any invalid or empty player entries
        $validPlayers = array_filter($players, function($player) {
            return is_array($player) && 
                   !empty($player['user_id']) && 
                   !empty($player['name']);
        });
        
        // Remove duplicates by user_id, keeping the last occurrence
        $uniquePlayers = [];
        foreach (array_reverse($validPlayers) as $player) {
            $userId = $player['user_id'];
            if (!isset($uniquePlayers[$userId])) {
                $uniquePlayers[$userId] = $player;
            }
        }
        
        // Reset array keys and return
        return array_values($uniquePlayers);
    }

    /**
     * Set the players attribute, ensuring it's properly formatted
     *
     * @param  mixed  $value
     * @return void
     */
    public function setPlayersAttribute($value)
    {
        $players = is_array($value) ? $value : [];
        
        // Ensure each player has required fields and filter out invalid ones
        $validPlayers = array_values(array_filter($players, function($player) {
            return is_array($player) && 
                   !empty($player['user_id']) && 
                   !empty($player['name']);
        }));
        
        $this->attributes['players'] = json_encode($validPlayers);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function choices()
    {
        return $this->hasMany(WagerChoice::class);
    }
}
