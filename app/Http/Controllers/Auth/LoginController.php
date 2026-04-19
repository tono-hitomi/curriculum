<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * ログイン処理のオーバーライド
     * 論理削除（利用停止）ユーザーを判定する
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // 論理削除されたユーザーも含めて検索
        $user = User::withTrashed()->where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // パスワードが一致し、かつ論理削除されている場合
            if ($user->trashed()) {
                // 指定されたパス resources/views/errors/suspended.blade.php を表示
                return response()->view('errors.suspended', compact('user'));
            }
        }

        // 通常のログイン処理を実行
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * ログイン直後のリダイレクト先を判定
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // is_adminカラムが 1 (管理者) の場合、管理者画面へ
        if ($user->is_admin === 1) {
            return redirect()->route('admin.index');
        }

        // それ以外（一般ユーザー）はホーム画面へ
        return redirect()->route('home');
    }
}