<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'firstname',
        'lastname',
        'username',
        'languagecode',
        'isbot',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
