<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * 管理者画面のメイン表示
     */
    public function index()
    {
        $users = User::withCount([
            'participatingEvents as applications_count', 
            'events as events_count'
        ])->get();

        // 主催者が削除されていないイベントのみ取得（報告数が多い順）
        $events = Event::whereHas('user')
            ->orderBy('report_count', 'desc')
            ->take(10)
            ->get();

        // 主催者または申込者が削除されていない参加申込のみ取得
        $applications = DB::table('event_user')
            ->join('users', 'event_user.user_id', '=', 'users.id')
            ->join('events', 'event_user.event_id', '=', 'events.id')
            ->join('users as owners', 'events.user_id', '=', 'owners.id') 
            ->select('users.name', 'users.email', 'events.title')
            ->whereNull('users.deleted_at')
            ->whereNull('owners.deleted_at')
            ->get()
            ->map(function($item) {
                return (object)[
                    'user' => (object)['name' => $item->name, 'email' => $item->email],
                    'event' => (object)['title' => $item->title]
                ];
            });
        
        return view("admin.index", compact("users", "events", "applications"));
    }

    /**
     * ユーザー一覧CSVエクスポート
     */
    public function exportUsers()
    {
        $users = User::withCount(['applications', 'events'])->get();
        return $this->generateCsv('users_list', ['ID', 'ユーザー名', 'メールアドレス', '参加数', '主催数', '登録日'], function($stream) use ($users) {
            foreach ($users as $user) {
                fputcsv($stream, [$user->id, $user->name, $user->email, $user->applications_count, $user->events_count, $user->created_at]);
            }
        });
    }

    /**
     * イベント一覧CSVエクスポート
     */
    public function exportEvents()
    {
        $events = Event::with('user')->get();
        return $this->generateCsv('events_list', ['ID', 'イベント名', '主催者', '報告数', '表示状態', '開催日'], function($stream) use ($events) {
            foreach ($events as $event) {
                fputcsv($stream, [$event->id, $event->title, $event->user->name ?? '不明', $event->report_count, $event->is_visible ? '表示' : '非表示', $event->date]);
            }
        });
    }

    /**
     * 参加申込一覧CSVエクスポート
     */
    public function exportApplications()
    {
        $apps = DB::table('event_user')
            ->join('users', 'event_user.user_id', '=', 'users.id')
            ->join('events', 'event_user.event_id', '=', 'events.id')
            ->select('users.name as u_name', 'users.email', 'events.title as e_title', 'event_user.created_at')
            ->get();

        return $this->generateCsv('applications_list', ['ユーザー名', 'メール', '対象イベント', '申込日時'], function($stream) use ($apps) {
            foreach ($apps as $app) {
                fputcsv($stream, [$app->u_name, $app->email, $app->e_title, $app->created_at]);
            }
        });
    }

    /**
     * CSV
     */
    private function generateCsv($filename, $header, $callback)
    {
        $response = new StreamedResponse(function () use ($header, $callback) {
            $stream = fopen('php://output', 'w');
            fwrite($stream, pack('C*', 0xEF, 0xBB, 0xBF)); 
            fputcsv($stream, $header);
            $callback($stream);
            fclose($stream);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '_' . date('Ymd') . '.csv"');
        return $response;
    }

    // --- 管理画面詳細機能 ---

    /**
     * 全イベント詳細一覧（報告数が多い順）
     */
    public function eventList() {
        $events = Event::whereHas('user')
            ->orderBy('report_count', 'desc') // 報告数が多い順
            ->orderBy('id', 'desc')
            ->paginate(20);
            
        return view('admin.events.index', compact('events'));
    }

    /**
     * 表示状態切り替え Ajax
     */
    public function toggleVisible(Event $event) {
        $event->is_visible = !$event->is_visible;
        $event->save();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_visible' => $event->is_visible
            ]);
        }

        return back()->with('status', '表示状態を更新しました');
    }

    public function userIndex() {
        $users = User::withTrashed()
            ->withCount(['participatingEvents as applications_count', 'events as events_count'])
            ->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function confirmSuspend(User $user) {
        return view('admin.users.suspend', compact('user'));
    }

    public function restore(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('admin.users.index')->with('status', 'ユーザーの利用停止を解除しました。');
    }

    public function suspend(User $user) {
        $user->delete();
        return redirect()->route('admin.users.index')->with('status', 'ユーザーを利用停止にしました。');
    }

    /**
     * 違反報告一覧
     */
public function reportIndex()
{
    $events = Event::with(['user', 'users'])
        ->withCount('users')
        ->orderBy('id', 'desc')
        ->paginate(10);

    return view('admin.reports.index', compact('events'));
}
    /**
     * 該当イベントの違反報告を一括削除
     */
    public function resetReports($eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // 関連する報告をすべて削除
        Report::where('event_id', $eventId)->delete();
        
        // カウントを0にする
        $event->report_count = 0;
        $event->save();

        return back()->with('status', 'イベントの違反報告をすべて削除しました。');
    }

    /**
     * 違反報告の削除
     */
    public function reportDestroy($id)
    {
        $report = Report::findOrFail($id);

        if ($report->event) {
            $report->event->decrement('report_count');
        }

        $report->delete();

        return back()->with('status', '違反報告を削除しました。');
    }
}