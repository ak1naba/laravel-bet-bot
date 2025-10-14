<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sport_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'status',
        'metadata',
    ];

    protected $dates = ['deleted_at'];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function result()
    {
        return $this->hasOne(EventResult::class);
    }

    public function markets()
    {
        return $this->hasMany(Market::class);
    }
}
