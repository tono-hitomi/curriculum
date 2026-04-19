<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * 認証ミドルウェアの設定
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * イベント一覧 (Resource: index)
     */
    public function index(Request $request)
    {
        // is_visible が 1 のものだけを取得
        $query = Event::with('user')->where('is_visible', 1);

        if ($request->filled('keyword')) {
            $keyword = '%' . $request->keyword . '%';
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', $keyword)
                  ->orWhere('comment', 'like', $keyword);
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('format')) {
            $query->where('format', $request->format);
        }

        $events = $query->latest()->get();
        
        return view('home', compact('events'));
    }

    /**
     * 新規作成画面
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * 保存処理
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $event = new Event();
        $event->title = $request->title;
        $event->comment = $request->description;
        $event->user_id = Auth::id();
        
        // --- 修正ポイント：新規作成時はデフォルトで「表示(1)」に設定 ---
        $event->is_visible = 1; 
        
        $event->capacity = 0; 
        $event->date = now(); 
        $event->format = 0; 
        $event->type = 0; 

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/event_images');
            $event->image = basename($path);
        }

        $event->save();

        return redirect()->route('mypage')->with('status', 'イベントを作成しました！');
    }

    /**
     * 詳細表示
     */
    public function show(Event $event)
    {
        $event->load(['user', 'users']);
        return view('events.show', compact('event'));
    }

    /**
     * 編集画面
     */
    public function edit(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }
        return view('events.edit', compact('event'));
    }

    /**
     * 更新処理
     */
    public function update(Request $request, Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $event->title = $request->title;
        $event->comment = $request->description;

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::delete('public/event_images/' . $event->image);
            }
            $path = $request->file('image')->store('public/event_images');
            $event->image = basename($path);
        }

        $event->save();

        return redirect()->route('mypage')->with('status', 'イベントを更新しました！');
    }

    /**
     * 削除処理
     */
    public function destroy(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        if ($event->image) {
            Storage::delete('public/event_images/' . $event->image);
        }

        $event->delete();

        return redirect()->route('mypage')->with('status', 'イベントを削除しました。');
    }

    /* --- ブックマーク関連 (Ajax対応) --- */

    public function bookmarkIndex()
    {
        $user = Auth::user();
        $bookmarkedEvents = $user->bookmarks()->latest()->paginate(8);
        return view('events.bookmarks', compact('bookmarkedEvents'));
    }

    public function bookmark(Event $event)
    {
        $user = Auth::user();
        
        if ($user->bookmarks()->where('event_id', $event->id)->exists()) {
            $user->bookmarks()->detach($event->id);
            $status = 'unbookmarked';
        } else {
            $user->bookmarks()->attach($event->id);
            $status = 'bookmarked';
        }

        if (request()->ajax()) {
            return response()->json(['status' => $status]);
        }

        return back();
    }

    public function unbookmark(Event $event)
    {
        Auth::user()->bookmarks()->detach($event->id);

        if (request()->ajax()) {
            return response()->json(['status' => 'unbookmarked']);
        }

        return back()->with('status', 'ブックマークを解除しました。');
    }

    /* --- ユーザー・マイページ関連 --- */

    public function mypage()
    {
        $user = Auth::user();
        return view('mypage', [
            'user' => $user,
            'participatingEvents' => $user->participatingEvents()->get(), 
            'myEvents' => $user->events()->get(),
            'bookmarkedEvents' => $user->bookmarks()->take(5)->get(),
        ]);
    }

    public function profile()
    {
        return view('profile', ['user' => Auth::user()]);
    }

    public function editProfile()
    {
        return view('profile_edit', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'introduction' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'introduction']);
        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::delete('public/profile_images/' . $user->image);
            }
            $path = $request->file('image')->store('public/profile_images');
            $data['image'] = basename($path);
        }

        $user->update($data);
        return redirect()->route('profile')->with('status', 'プロフィールを更新しました！');
    }

    public function confirmDelete()
    {
        return view('auth.delete_confirm', ['user' => Auth::user()]);
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        $request->validate(['password' => 'required']);

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'パスワードが正しくありません。');
        }
        
        $user->delete();
        Auth::logout();
        return redirect('/')->with('status', '退会手続きが完了しました。');
    }

    /* --- 参加申込関連 --- */

    public function apply(Event $event)
    {
        $event->load('user');
        return view('events.apply', compact('event'));
    }

    public function storeApplication(Request $request, Event $event)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        if (!$event->users()->where('user_id', Auth::id())->exists()) {
            $event->users()->attach(Auth::id(), [
                'comment' => $request->comment
            ]);
        }
        return redirect()->route('events.show', $event->id)->with('status', 'イベントへの参加を申し込みました！');
    }

    public function cancel(Event $event)
    {
        $event->users()->detach(Auth::id());
        return back()->with('status', '参加申込をキャンセルしました。');
    }

    /* --- 違反報告関連 --- */

    public function report(Event $event)
    {
        $event->load('user');
        return view('events.report', compact('event'));
    }

    public function storeReport(Request $request, Event $event)
    {
        $request->validate([
            'report_comment' => 'required|string|max:1000',
        ]);

        Report::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'title' => $event->title,
            'content' => $request->report_comment,
        ]);

        $event->increment('report_count');

        return redirect()->route('home')->with('status', '違反報告を送信しました。');
    }
}