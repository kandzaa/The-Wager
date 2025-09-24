<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WagerChoice extends Model
{
    protected $fillable = [
        'wager_id',
        'label',
        'sort_order',
        'total_bet',
    ];

    protected $casts = [
        'total_bet'  => 'decimal:2',
        'sort_order' => 'integer',
    ];

    protected $attributes = [
        'total_bet'  => 0,
        'sort_order' => 0,
    ];

    public function wager()
    {
        return $this->belongsTo(Wager::class);
    }
}
