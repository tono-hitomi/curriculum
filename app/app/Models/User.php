<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * 保存を許可するカラム
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'introduction', // 自己紹介
        'image',
    ];

    /**
     * 表示させないカラム
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 型変換の設定
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * ブックマークしているイベント
     */
    public function bookmarks()
    {
        // Eventモデルの場所をフルパスで指定して、エラーを防
        return $this->belongsToMany(\App\Models\Event::class, 'bookmarks');
    }

    /**
     * 参加しているイベント
     */
    public function events()
    {
        return $this->belongsToMany(\App\Models\Event::class, 'event_user')
                    ->withPivot('comment')
                    ->withTimestamps();
    }
}