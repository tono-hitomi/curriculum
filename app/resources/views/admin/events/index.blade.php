@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">全イベント詳細（管理者）</h2>
    
    <table class="table table-bordered text-center">
        <thead>
            <tr class="table-secondary">
                <th>タイトル</th>
                <th>違反報告数</th>
                <th>非表示設定</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td class="text-left">
                    <a href="{{ route('events.show', $event) }}" class="text-primary">
                        {{ $event->title }}
                    </a>
                </td>
                <td>{{ $event->report_count }}件</td>
                <td>
                    {{-- classを追加し、JavaScriptで扱いやすいように data-id を付与しています --}}
                    <button type="button" 
                        class="btn {{ $event->is_visible ? 'btn-warning' : 'btn-success' }} btn-sm toggle-visible-btn" 
                        data-id="{{ $event->id }}"
                        style="width: 120px;">
                        {{ $event->is_visible ? '非表示にする' : '表示する' }}
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ページネーション --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $events->links() }}
    </div>

    {{-- メインへ戻る --}}
    <div class="text-right mt-3">
        <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">メインへ</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 全ての切り替えボタンを取得
    const buttons = document.querySelectorAll('.toggle-visible-btn');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const eventId = this.dataset.id;
            const url = `/admin/events/${eventId}/toggle-visible`;

            // ボタンを一時的に無効化（連続クリック防止）
            this.disabled = true;

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest', // これが request()->ajax() の判定に使われます
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('通信エラーが発生しました');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // 状態に応じてボタンの見た目を更新
                    if (data.is_visible) {
                        this.classList.remove('btn-success');
                        this.classList.add('btn-warning');
                        this.textContent = '非表示にする';
                    } else {
                        this.classList.remove('btn-warning');
                        this.classList.add('btn-success');
                        this.textContent = '表示する';
                    }
                }
            })
            .catch(error => {
                alert(error.message);
            })
            .finally(() => {
                // ボタンを再度有効化
                this.disabled = false;
            });
        });
    });
});
</script>
@endsection