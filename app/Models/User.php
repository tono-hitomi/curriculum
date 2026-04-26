<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; 

class User extends Authenticatable
{
    use Notifiable, SoftDeletes; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'image', 'is_admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 自分が作成（主催）したイベント
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * 自分が参加を申し込んだイベント
     */
    public function participatingEvents()
    {
        return $this->belongsToMany(Event::class, 'event_user')->withPivot('comment');
    }

    /**
     * ブックマーク
     */
    public function bookmarks()
    {
        return $this->belongsToMany(Event::class, 'bookmarks', 'user_id', 'event_id');
    }

    public function bookmarkedEvents()
    {
        return $this->bookmarks();
    }

    /**
     * ユーザーが申し込んだイベント（参加申込）
     */
    public function applications()
    {
        // Eventモデルとの多対多リレーション
        return $this->belongsToMany(Event::class, 'event_user')->withPivot('comment');
    }
}