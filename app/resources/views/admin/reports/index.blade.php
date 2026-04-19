@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">違反報告一覧</h2>

    @if (session('status'))
        <div class="alert alert-success shadow-sm mb-4">{{ session('status') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <table class="table table-hover text-center mb-0">
            <thead class="table-secondary">
                <tr>
                    <th>報告者</th>
                    <th>対象イベント</th>
                    <th>報告内容</th>
                    <th>報告日時</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr>
                    <td>{{ $report->user->name }}</td>
                    <td class="text-left">
                        <a href="/events/{{ $report->event_id }}" target="_blank">
                            {{ $report->event->title ?? '削除済みのイベント' }}
                        </a>
                    </td>
                    <td class="text-left" style="max-width: 300px;">
                        <small>{{ $report->content }}</small>
                    </td>
                    <td>{{ $report->created_at->format('Y/m/d H:i') }}</td>
                    <td>
                        {{-- シンプルなGETリクエストによる削除 --}}
                        <a href="/admin/delete-report/{{ $report->id }}" 
                           class="btn btn-outline-danger btn-sm"
                           onclick="return confirm('この報告を削除（棄却）しますか？');">
                            報告を削除
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-muted py-4">現在、違反報告はありません。</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $reports->links() }}
    </div>

    <div class="text-right mt-3">
        <a href="/admin" class="btn btn-outline-secondary">メインへ戻る</a>
    </div>
</div>
@endsection