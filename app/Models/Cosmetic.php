<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cosmetic extends Model
{
    protected $fillable = ['key', 'name', 'type', 'rarity', 'price', 'meta'];

    protected $casts = [
        'meta' => 'array',
    ];

    public function owners()
    {
        return $this->belongsToMany(User::class, 'user_cosmetics');
    }
}