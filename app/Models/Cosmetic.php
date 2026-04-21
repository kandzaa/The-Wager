<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cosmetic extends Model
{
    protected $fillable = ['name', 'type', 'rarity', 'price', 'meta'];

    public function getMetaAttribute($value): array
    {
        if (is_null($value) || $value === '') return [];
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }
}