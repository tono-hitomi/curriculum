@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 font-weight-bold">違反報告一覧</h2>

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
                    <th>表示 / 非表示</th> {{-- ★列を分離 --}}
                    <th>報告削除</th>       {{-- ★列を分離 --}}
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr>
                    <td>{{ $report->user->name }}</td>
                    <td class="text-left">
                        <a href="/events/{{ $report->event_id }}?from=admin_report" class="font-weight-bold text-primary">
                            {{ $report->event->title ?? '削除済みのイベント' }}
                        </a>
                    </td>
                    <td class="text-left" style="max-width: 250px;">
                        <small class="text-muted">{{ $report->content }}</small>
                    </td>
                    <td>{{ $report->created_at->format('Y/m/d H:i') }}</td>
                    
                    {{-- ★表示 / 非表示ボタンの列 --}}
                    <td>
                        @if($report->event)
                            <button type="button" 
                                class="btn {{ $report->event->is_visible ? 'btn-warning' : 'btn-success' }} btn-sm px-3 toggle-visible-btn" 
                                data-id="{{ $report->event_id }}"
                                style="width: 85px;">
                                {{ $report->event->is_visible ? '非表示' : '表示' }}
                            </button>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>

                    {{-- ★報告削除ボタンの列 --}}
                    <td>
                        <a href="/admin/delete-report/{{ $report->id }}" 
                           class="btn btn-outline-danger btn-sm px-3"
                           onclick="return confirm('この報告を削除（棄却）しますか？');">
                            報告を削除
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-muted py-4">現在、違反報告はありません。</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $reports->links() }}
    </div>

    <div class="text-right mt-3">
        <a href="/admin" class="btn btn-outline-secondary px-4">
            <i class="fas fa-home"></i> メインへ戻る
        </a>
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
                    if (data.is_visible) {
                        this.classList.remove('btn-success');
                        this.classList.add('btn-warning');
                        this.textContent = '非表示';
                    } else {
                        this.classList.remove('btn-warning');
                        this.classList.add('btn-success');
                        this.textContent = '表示';
                    }
                }
            })
            .catch(error => {
                alert(error.message);
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
});
</script>
@endsection