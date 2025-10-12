<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bet extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'duplicate_user',
        'market_id',
        'duplicate_market',
        'odds_id',
        'duplicate_odds',
        'amount',
        'status',
        'payout',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function market(){
        return $this->belongsTo(Market::class);
    }

    public function odd()
    {
        return $this->belongsTo(Odd::class);
    }

}
