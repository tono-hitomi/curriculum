<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    
    protected $fillable = [
        'user_id', 
        'title', 
        'description', // 詳細文
        'image',       // 画像パス
        'capacity', 
        'date', 
        'format', 
        'type'
    ];

    /**
     * 主催者
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * 参加者
     */
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'event_user')
                    ->withPivot('comment')
                    ->withTimestamps();
    }

    /**
     * 参加済み判定メソッド
     */
    public function isJoined($userId)
    {
        // このイベントに参加しているユーザーの中に、$userId が存在するかチェック
        return $this->users()->where('user_id', $userId)->exists();
    }
}