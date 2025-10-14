<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Market extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'duplicate_event',
        'type',
        'description',
        'participant_id',
        'duplicate_participant',
    ];

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function participant()
    {
        return $this->belongsTo(EventParticipant::class);
    }

    public function odds()
    {
        return $this->hasMany(Odd::class);
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }
}
