@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 font-weight-bold">全イベント詳細（管理者）</h2>
        <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-home"></i> メインへ
        </a>
    </div>
    
    <div class="card shadow-sm border-0">
        <table class="table table-hover text-center mb-0">
            <thead>
                <tr class="table-secondary">
                    <th style="width: 25%;">タイトル</th>
                    <th style="width: 10%;">報告数</th>
                    <th style="width: 35%;">最新の報告内容 / 日時</th>
                    <th style="width: 15%;">非表示設定</th>
                    <th style="width: 15%;">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                <tr class="{{ $event->report_count > 0 ? 'bg-light' : '' }}">
                    {{-- タイトル --}}
                    <td class="text-left align-middle">
                        <a href="{{ route('events.show', $event) }}?from=admin_events" class="text-primary font-weight-bold">
                            {{ $event->title }}
                        </a>
                    </td>

                    {{-- 報告数 --}}
                    <td class="align-middle">
                        <span class="badge {{ $event->report_count > 0 ? 'badge-danger' : 'badge-secondary' }} p-2">
                            {{ $event->report_count }}件
                        </span>
                    </td>

                    {{-- 報告内容と日時 --}}
                    <td class="text-left align-middle small">
                        @php
                            // 最新の報告を1件取得
                            $latestReport = \App\Models\Report::where('event_id', $event->id)->latest()->first();
                        @endphp

                        @if($latestReport)
                            <div class="font-weight-bold text-danger mb-1">
                                <i class="fas fa-comment-dots"></i> {{ $latestReport->content }}
                            </div>
                            <div class="text-muted">
                                <i class="far fa-clock"></i> {{ $latestReport->created_at->format('Y/m/d H:i') }}
                            </div>
                        @else
                            <span class="text-muted">報告なし</span>
                        @endif
                    </td>

                    {{-- 非表示ボタン --}}
                    <td class="align-middle">
                        <button type="button" 
                            class="btn {{ $event->is_visible ? 'btn-warning' : 'btn-success' }} btn-sm toggle-visible-btn" 
                            data-id="{{ $event->id }}"
                            style="width: 110px;">
                            {{ $event->is_visible ? '非表示にする' : '表示する' }}
                        </button>
                    </td>

                    {{-- 報告削除（リセット） --}}
                    <td class="align-middle">
                        @if($event->report_count > 0)
                            <form action="{{ route('admin.reports.destroy', $event->id) }}" method="POST" onsubmit="return confirm('このイベントの報告をすべて削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-undo"></i> 報告をクリア
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ページネーション --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $events->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.toggle-visible-btn');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const eventId = this.dataset.id;
            const url = `/admin/events/${eventId}/toggle-visible`;

            this.disabled = true;

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest', 
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('通信エラーが発生しました');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    this.classList.toggle('btn-success', !data.is_visible);
                    this.classList.toggle('btn-warning', data.is_visible);
                    this.textContent = data.is_visible ? '非表示にする' : '表示する';
                }
            })
            .catch(error => alert(error.message))
            .finally(() => this.disabled = false);
        });
    });
});
</script>
@endsection