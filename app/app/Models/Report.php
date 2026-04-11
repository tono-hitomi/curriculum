<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    // 保存を許可するカラムを指定
    protected $fillable = ['user_id', 'event_id', 'title','content'];
}
