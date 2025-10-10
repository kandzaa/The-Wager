<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WagerInvitation extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';
    const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'wager_id',
        'inviter_id',
        'invitee_id',
        'email',
        'token',
        'status',
        'expires_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            $invitation->token = $invitation->generateToken();
            $invitation->expires_at = now()->addDays(7); // Invitations expire after 7 days
        });
    }

    /**
     * Generate a unique token for the invitation.
     */
    public function generateToken()
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    /**
     * Check if the invitation is expired.
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Mark the invitation as accepted.
     */
    public function accept()
    {
        $this->status = self::STATUS_ACCEPTED;
        $this->accepted_at = now();
        $this->save();
    }

    /**
     * Mark the invitation as declined.
     */
    public function decline()
    {
        $this->status = self::STATUS_DECLINED;
        $this->declined_at = now();
        $this->save();
    }

    /**
     * Scope a query to only include pending invitations.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING)
                    ->where('expires_at', '>', now());
    }

    /**
     * Get the wager that the invitation belongs to.
     */
    public function wager()
    {
        return $this->belongsTo(Wager::class)->with('creator');
    }

    /**
     * Get the user who created the invitation.
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    /**
     * Get the user who was invited.
     */
    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }
}
