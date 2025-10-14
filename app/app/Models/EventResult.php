<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventResult extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'duplicate_event',
        'winner_id',
        'duplicate_winner',
        'metadata',
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function winner()
    {
        return $this->belongsTo(EventParticipant::class);
    }
}
