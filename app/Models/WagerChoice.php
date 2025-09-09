<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WagerChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'wager_id',
        'label',
        'total_bet',
    ];

    public function wager()
    {
        return $this->belongsTo(Wager::class);
    }
}
