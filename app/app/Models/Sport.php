<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sport extends Model
{
    use SoftDeletes;

    public const PER_PAGE = 5;

    protected $fillable = [
        'name',
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
