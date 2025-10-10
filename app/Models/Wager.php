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
        return $this->hasMany(WagerChoice::class);
    }

    public function winningChoice()
    {
        return $this->belongsTo(WagerChoice::class, 'winning_choice_id');
    }

    /**
     * Scope a query to only include wagers that the user has participated in.
     */
    public function scopeUserParticipated($query, $userId)
    {
        return $query->whereHas('players', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->orWhere('creator_id', $userId);
    }

    /**
     * Scope a query to only include ended wagers.
     */
    public function scopeEnded($query)
    {
        return $query->where('status', 'ended')
                    ->whereNotNull('ended_at')
                    ->latest('ended_at');
    }

    /**
     * Scope a query to only include public wagers.
     */
    public function scopePublic($query)
    {
        return $query->where('status', 'public');
    }

    /**
     * Get the URL to the wager's results page.
     */
    public function getResultsUrlAttribute()
    {
        return route('wagers.results', $this);
    }

    /**
     * Get the time since the wager ended in a human-readable format.
     */
    public function getTimeSinceEndedAttribute()
    {
        return $this->ended_at ? $this->ended_at->diffForHumans() : null;
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

    /**
     * Get all invitations for this wager.
     */
    public function invitations()
    {
        return $this->hasMany(\App\Models\WagerInvitation::class);
    }

    /**
     * Get pending invitations for this wager.
     */
    public function pendingInvitations()
    {
        return $this->invitations()->where('status', \App\Models\WagerInvitation::STATUS_PENDING)
                                 ->where('expires_at', '>', now());
    }

    /**
     * Check if a user is invited to this wager.
     */
    public function isUserInvited($email)
    {
        return $this->invitations()
                   ->where('email', $email)
                   ->where('status', \App\Models\WagerInvitation::STATUS_PENDING)
                   ->where('expires_at', '>', now())
                   ->exists();
    }

    /**
     * Invite a user to this wager.
     */
    public function inviteUser($email, $inviterId)
    {
        return \App\Models\WagerInvitation::create([
            'wager_id' => $this->id,
            'inviter_id' => $inviterId,
            'email' => $email,
            'status' => \App\Models\WagerInvitation::STATUS_PENDING,
        ]);
    }
}
