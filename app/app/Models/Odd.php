<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Odd extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'market_id',
        'duplicate_market',
        'value',
    ];

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function bet()
    {
        return $this->hasMany(Bet::class);
    }
}
