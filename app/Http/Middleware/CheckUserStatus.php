<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * 特定のユーザーが利用停止（ソフトデリート）されているかチェック
     */
    public function handle(Request $request, Closure $next)
    {
        // ログインしていて、かつそのユーザーが削除（ソフトデリート）されている場合
        if (Auth::check() && Auth::user()->trashed()) {
            Auth::logout(); // 強制ログアウト
            
            // resources/views/errors/suspended.blade.php を表示
            return response()->view('errors.suspended');
        }

        return $next($request);
    }
}