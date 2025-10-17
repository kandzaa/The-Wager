<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'role',
        'last_daily_claim_at',
    ];

    protected $attributes = [
        'role'    => 'user',
        'balance' => 0, // Optional: Set default balance
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted()
    {
        static::saving(function ($user) {
            if (! in_array($user->role, ['user', 'admin'])) {
                throw new \InvalidArgumentException("Invalid role. Must be either 'user' or 'admin'");
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at'   => 'datetime',
            'password'            => 'hashed',
            'balance'             => 'integer',
            'last_daily_claim_at' => 'datetime',
        ];
    }

    /**
     * Get all wagers created by this user.
     */
    public function wagers()
    {
        return $this->hasMany(Wager::class, 'creator_id');
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'user_friends', 'user_id', 'friend_id');
    }

    /**
     * Get all wager players for the user.
     */
    public function wagerPlayers()
    {
        return $this->hasMany(\App\Models\WagerPlayer::class, 'user_id');
    }

    /**
     * Get all wager invitations for the user.
     */
    public function wagerInvitations()
    {
        return $this->hasMany(\App\Models\WagerInvitation::class, 'invitee_id');
    }
}
