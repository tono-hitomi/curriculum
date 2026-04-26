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
     * イベント一覧 ( index)
     */
public function index(Request $request)
{
    $query = Event::where('is_visible', true);

    // キーワード検索
    if ($request->filled('keyword')) {
        $query->where('title', 'like', '%' . $request->keyword . '%');
    }

    // 期間指定（From）
    if ($request->filled('from_date')) {
        $query->whereDate('date', '>=', $request->from_date);
    }

    // 期間指定（To）
    if ($request->filled('to_date')) {
        $query->whereDate('date', '<=', $request->to_date);
    }

    // 形式検索
    if ($request->filled('format')) {
        $query->where('format', $request->format);
    }

    $events = $query->orderBy('date', 'asc')->get();

    return view('home', compact('events'));
}    /**
     * 新規作成画面
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * 保存処理 (マイページへ遷移)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'date' => 'required|date|after:now', 
            'format' => 'required|string', 
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
            'capacity' => 'nullable|integer|min:1',
        ]);

        $event = new Event();
        $event->user_id = Auth::id(); 
        $event->title = $request->title;
        $event->comment = $request->description; 
        $event->date = $request->date;
        
        $event->format = $request->format;
        
        $event->capacity = $request->capacity;

        $event->is_visible = true;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/event_images');
            $event->image = basename($path);
        }

        $event->save();

        return redirect()->route('mypage')->with('status', 'イベントを公開しました！');
    }

    /**
     * 詳細表示
     */
    public function show(Event $event)
    {
        if (!$event->user || $event->user->trashed()) {
        return response()->view('errors.event_deleted', [], 404);
    }
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
            'date' => 'required|date',
            'format' => 'required|string',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $event->title = $request->title;
        $event->comment = $request->description;
        $event->date = $request->date;
        $event->format = $request->format; 
        $event->capacity = $request->capacity;

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

    /* --- ブックマーク、マイページ、参加、報告 --- */

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
        return request()->ajax() ? response()->json(['status' => $status]) : back();
    }

    public function unbookmark(Event $event)
    {
        Auth::user()->bookmarks()->detach($event->id);
        return request()->ajax() ? response()->json(['status' => 'unbookmarked']) : back()->with('status', '解除完了');
    }

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
        return redirect()->route('profile')->with('status', 'プロフィール更新完了');
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
            return back()->with('error', 'パスワードが違います');
        }
        $user->delete();
        Auth::logout();
        return redirect('/')->with('status', '退会しました');
    }

    public function apply(Event $event)
    {
        $event->load('user');
        return view('events.apply', compact('event'));
    }

    public function storeApplication(Request $request, Event $event)
    {
        $request->validate(['comment' => 'required|string|max:1000']);

        // すでに応募済みでないか確認
        if ($event->users()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('events.show', $event->id)->with('error', '既に応募済みです。');
        }

        // 定員チェック (capacityが設定されている場合のみ)
        if (!is_null($event->capacity)) {
            $count = $event->users()->count();
            if ($count >= $event->capacity) {
                return redirect()->route('events.show', $event->id)->with('error', '定員に達したため申し込みできません。');
            }
        }

        // 登録処理
        $event->users()->attach(Auth::id(), ['comment' => $request->comment]);

        return redirect()->route('events.show', $event->id)->with('status', '参加申込完了');
    }

    public function cancel(Event $event)
    {
        $event->users()->detach(Auth::id());
        return back()->with('status', 'キャンセルしました');
    }

    public function report(Event $event)
    {
        $event->load('user');
        return view('events.report', compact('event'));
    }

    public function storeReport(Request $request, Event $event)
    {
        $request->validate(['report_comment' => 'required|string|max:1000']);
        Report::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'title' => $event->title,
            'content' => $request->report_comment,
        ]);
        $event->increment('report_count');
        return redirect()->route('home')->with('status', '報告しました');
    }
}