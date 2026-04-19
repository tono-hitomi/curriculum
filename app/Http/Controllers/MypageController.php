<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // ファイル削除に必要
use Illuminate\Support\Facades\Hash;

class MypageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // 参加・主催・ブックマークのデータを取得
        // ※リレーション名が違うと後でエラーが出るかもしれませんが、まずはこれで進めます
        $participatingEvents = $user->participatingEvents()->get();
        $myEvents = $user->events()->get();
        $bookmarkedEvents = $user->bookmarkedEvents()->get();

        return view('mypage', compact('participatingEvents', 'myEvents', 'bookmarkedEvents'));
    }

    public function profile()
{
    // 現在ログインしているユーザーの情報を渡す
    return view('profile', ['user' => Auth::user()]);
}

public function editProfile()
{
    return view('profile_edit', ['user' => Auth::user()]);
}

/**
 * プロフィール更新処理
 */
public function updateProfile(Request $request)
{
    $user = Auth::user();

    // バリデーション
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'introduction' => 'nullable|string|max:1000',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // 基本データの更新準備
    $user->name = $request->name;
    $user->email = $request->email;
    $user->introduction = $request->introduction;

    // 画像がアップロードされた場合の処理
    if ($request->hasFile('image')) {
        // 古い画像があれば削除
        if ($user->image) {
            Storage::delete('public/profile_images/' . $user->image);
        }
        // 新しい画像を保存
        $path = $request->file('image')->store('public/profile_images');
        $user->image = basename($path);
    }

    $user->save();

    return redirect()->route('profile')->with('status', 'プロフィールを更新しました！');
}

public function confirmDelete()
{
    return view('auth.delete_confirm', ['user' => Auth::user()]);
}

/**
 * 退会実行（アカウント削除）
 */
public function deleteAccount(Request $request)
{
    $user = Auth::user();

    // 1. バリデーション（入力チェック）
    $request->validate([
        'name' => 'required',
        'password' => 'required',
    ]);

    // 2. ユーザー名とパスワードの照合
    if ($request->name !== $user->name || !Hash::check($request->password, $user->password)) {
        return back()->with('error', 'ユーザー名またはパスワードが正しくありません。');
    }

    // 3. データの削除
    // ※画像がある場合は削除しておくとストレージが節約できます
    if ($user->image) {
        Storage::delete('public/profile_images/' . $user->image);
    }

    $user->delete();

    // 4. ログアウトさせてリダイレクト
    Auth::logout();
    return redirect('/')->with('status', '退会手続きが完了しました。ご利用ありがとうございました。');
}

}