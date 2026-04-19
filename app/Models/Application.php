<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    // 参加者を取得（Userがapp直下にある場合）
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    // イベントを取得（Eventがapp直下にある場合）
    public function event()
    {
        return $this->belongsTo(\App\Event::class);
    }
}