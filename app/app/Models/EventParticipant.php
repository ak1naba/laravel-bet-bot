<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Event;
use App\Models\Team;

class EventParticipant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'duplicate_event',
        'team_id',
        'duplicate_team',
    ];

    protected $dates = [
        'deleted_at'
    ];

    
    protected static function booted(): void
    {
        static::creating(function (self $participant) {
            $participant->duplicate_event = Event::find($participant->event_id)?->title;
            $participant->duplicate_team = Team::find($participant->team_id)?->name;
        });
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }


}
