<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Market;
use App\Models\Odd as OddModel;
use App\Models\User as UserModel;

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

    protected $dates = ['deleted_at'];

    protected static function booted(): void
    {
        static::creating(function (self $bet) {
            if ($bet->user_id) {
                $bet->duplicate_user = UserModel::find($bet->user_id)?->name;
            }

            if ($bet->market_id) {
                $bet->duplicate_market = Market::find($bet->market_id)?->description;
            }

            if ($bet->odds_id) {
                $bet->duplicate_odds = OddModel::find($bet->odds_id)?->value;
            }
        });
    }

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
