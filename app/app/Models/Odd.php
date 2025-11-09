<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Market;

class Odd extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'market_id',
        'duplicate_market',
        'value',
    ];

    protected $dates = ['deleted_at'];

    protected static function booted(): void
    {
        static::creating(function (self $odd) {
            if ($odd->market_id) {
                $odd->duplicate_market = Market::find($odd->market_id)?->description;
            }
        });
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function bet()
    {
        return $this->hasMany(Bet::class);
    }
}
