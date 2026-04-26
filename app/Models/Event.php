<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // 保存可能な項目の設定
    protected $fillable = [
        'user_id', 
        'title', 
        'comment',      
        'image', 
        'capacity', 
        'date', 
        'format', 
        'type',
        'report_count', 
        'is_visible'
    ];

    /**
     * 主催者 (一対多: 逆)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 参加者 (多対多のリレーション)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user')
                    ->withPivot('comment')
                    ->withTimestamps();
    }

    /**
     * 参加済み判定メソッド
     */
    public function isJoined($userId)
    {
        return $this->users()->where('user_id', $userId)->exists();
    }

    /**
     * ブックマークしているユーザー (多対多)
     */
    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'bookmarks')
                    ->withTimestamps();
    }

    /**
     * 違反報告一覧 (一対多)
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}