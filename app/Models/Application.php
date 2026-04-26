<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function event()
    {
        return $this->belongsTo(\App\Event::class);
    }
}