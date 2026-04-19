<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'title',    // 報告時のイベント名を記録
        'content',  // 報告理由
    ];

    // 報告したユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 報告対象のイベント
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}