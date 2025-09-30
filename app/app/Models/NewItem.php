<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewItem extends Model
{
    protected $table = 'new_items';

    protected $fillable = [
        'user_id',
        'title',
        'text',
        'published',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
