<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
