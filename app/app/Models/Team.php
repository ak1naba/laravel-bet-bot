<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    protected $fillable = [
      'name',
      'sport_id',
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function participant()
    {
        return $this->belongsTo(EventParticipant::class);
    }
}
