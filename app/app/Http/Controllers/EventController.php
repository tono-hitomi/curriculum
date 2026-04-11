<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * イベント一覧 (Resource: index)
     */
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->filled('keyword')) {
            $keyword = '%' . $request->keyword . '%';
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', $keyword)
                  ->orWhere('comment', 'like', $keyword);
            });
        }

        if ($request->filled('date')) {
            $query->where('date', '>=', $request->date);
        }

        if ($request->filled('format')) {
            $query->where('format', $request->format);
        }

        $events = $query->get();
        return view('home', compact('events'));
    }

    /**
     * 新規作成画面 (Resource: create)
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * 保存処理 (Resource: store)
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
     * 詳細表示 (Resource: show)
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    /**
     * 編集画面 (Resource: edit)
     */
    public function edit(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }
        return view('events.edit', compact('event'));
    }

    /**
     * 更新処理 (Resource: update)
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
            $path = $request->file('image')->store('public/event_images');
            $event->image = basename($path);
        }

        $event->save();

        return redirect()->route('mypage')->with('status', 'イベントを更新しました！');
    }

    /**
     * 削除処理 (Resource: destroy)
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

    /* --- リソース外の独自カスタムメソッド --- */

    public function apply(Event $event)
    {
        $event->load('user');
        return view('events.apply', compact('event'));
    }

    public function storeApplication(Request $request, Event $event)
    {
        $event->users()->attach(Auth::id(), [
            'comment' => $request->comment
        ]);
        return redirect()->route('home')->with('status', '申込が完了しました！');
    }

    public function report(Event $event)
    {
        $event->load('user');
        return view('events.report', compact('event'));
    }

    public function storeReport(Request $request, Event $event)
    {
        Report::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'title' => $event->title,
            'content' => $request->report_comment,
        ]);
        return redirect()->route('home')->with('status', '違反報告を送信しました。');
    }

    public function bookmark(Event $event)
    {
        $user = Auth::user();
        if ($user->bookmarks()->where('event_id', $event->id)->exists()) {
            $user->bookmarks()->detach($event->id);
        } else {
            $user->bookmarks()->attach($event->id);
        }
        return back();
    }

    public function mypage()
    {
        $user = Auth::user();
        return view('mypage', [
            'user' => $user,
            'participatingEvents' => $user->events()->get(), 
            'myEvents' => Event::where('user_id', $user->id)->get(),
            'bookmarkedEvents' => $user->bookmarks()->get(),
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
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'パスワードが正しくありません。');
        }
        $user->delete();
        Auth::logout();
        return redirect('/')->with('status', '退会手続きが完了しました。');
    }
}