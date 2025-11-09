<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Event;
use App\Models\EventParticipant;

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

    protected $dates = ['deleted_at'];

    protected static function booted(): void
    {
        static::creating(function (self $market) {
            $market->duplicate_event = Event::find($market->event_id)?->title;

            if ($market->participant_id) {
                $participant = EventParticipant::find($market->participant_id);
                $market->duplicate_participant = $participant?->duplicate_team ?? $participant?->team?->name;
            }
        });
    }

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
