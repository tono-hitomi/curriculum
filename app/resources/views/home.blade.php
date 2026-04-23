@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            {{-- 成功メッセージの表示 --}}
            @if (session('status'))
                <div class="alert alert-success shadow-sm" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            {{-- 検索 --}}
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white font-weight-bold text-muted border-bottom-0">
                    <i class="fas fa-search"></i> イベントを絞り込む
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('events.index') }}" method="GET" class="form-inline justify-content-center">
                        <div class="form-group mb-2">
                            <input type="text" name="keyword" class="form-control mr-2" placeholder="キーワード" value="{{ request('keyword') }}">
                        </div>

                        <div class="form-group mb-2">
                            <input type="date" name="date" class="form-control mr-2" value="{{ request('date') }}">
                        </div>

                        <div class="form-group mb-2">
                            <select name="format" class="form-control mr-2">
                                <option value="">配信形式を選択</option>
                                {{-- valueを数値(0,1..)から文字列(Zoom, YouTube..)に変更 --}}
                                <option value="Zoom" {{ request('format') === 'Zoom' ? 'selected' : '' }}>Zoom</option>
                                <option value="YouTube" {{ request('format') === 'YouTube' ? 'selected' : '' }}>YouTube</option>
                                <option value="対面" {{ request('format') === '対面' ? 'selected' : '' }}>対面</option>
                                <option value="その他" {{ request('format') === 'その他' ? 'selected' : '' }}>その他</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mb-2 px-4 shadow-sm">検索</button>
                        <a href="{{ route('events.index') }}" class="btn btn-outline-secondary mb-2 ml-2">クリア</a>
                    </form>
                </div>
            </div>

            {{-- メイン一覧 --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold">開催イベント一覧</h5>
                    @auth
                        <a href="{{ route('mypage') }}" class="btn btn-sm btn-outline-primary px-3 shadow-sm">
                            <i class="fas fa-user-circle"></i> マイページへ
                        </a>
                    @endauth
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 40%" class="border-top-0 pl-4">イベント内容</th>
                                    <th style="width: 20%" class="border-top-0 text-center">主催者</th>
                                    <th style="width: 20%" class="border-top-0 text-center">定員状況</th>
                                    <th style="width: 20%" class="border-top-0 text-right pr-4">アクション</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $event)
                                    <tr>
                                        <td class="align-middle pl-4">
                                            {{-- 文字列によるバッジ表示の出し分け --}}
                                            @if($event->format === 'Zoom')
                                                <span class="badge badge-info mb-1 text-white">Zoom</span>
                                            @elseif($event->format === 'YouTube')
                                                <span class="badge badge-danger mb-1">YouTube</span>
                                            @elseif($event->format === '対面')
                                                <span class="badge badge-success mb-1">対面</span>
                                            @else
                                                <span class="badge badge-secondary mb-1">その他</span>
                                            @endif
                                            
                                            <div class="font-weight-bold text-primary h6 mb-1">{{ $event->title }}</div>
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($event->date)->format('Y/m/d') }}
                                            </small>
                                        </td>
                                        <td class="align-middle text-center text-muted small">
                                            <i class="far fa-user-circle"></i> {{ $event->user->name ?? '不明' }}
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="font-weight-bold {{ (!is_null($event->capacity) && $event->users->count() >= $event->capacity) ? 'text-danger' : 'text-dark' }}">
                                                {{ $event->users->count() }}
                                            </span>
                                            <span class="text-muted small"> / {{ $event->capacity ?? '∞' }} 名</span>
                                        </td>
                                        <td class="align-middle text-right pr-4">
                                            <div class="d-flex justify-content-end align-items-center">
                                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-info text-white px-3 shadow-sm mr-2">詳細</a>

                                                @auth
                                                    <button type="button" 
                                                            class="ajax-bookmark-btn" 
                                                            data-id="{{ $event->id }}"
                                                            style="border: none; background: none; outline: none; transition: transform 0.2s; padding: 0;">
                                                        @if(Auth::user()->bookmarks()->where('event_id', $event->id)->exists())
                                                            <span class="star-icon" style="color: #f1c40f; font-size: 1.3rem;">★</span>
                                                        @else
                                                            <span class="star-icon" style="color: #ccc; font-size: 1.3rem;">☆</span>
                                                        @endif
                                                    </button>
                                                @else
                                                    <span style="color: #ccc; font-size: 1.3rem; opacity: 0.5;">☆</span>
                                                @endauth
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="fas fa-calendar-times fa-3x mb-3 d-block opacity-50"></i>
                                            該当するイベントは見つかりませんでした。
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .card { border-radius: 10px; overflow: hidden; }
    .table thead th { font-size: 0.85rem; letter-spacing: 0.05em; text-transform: uppercase; }
    .ajax-bookmark-btn:hover { transform: scale(1.3); cursor: pointer; }
    .btn-info { background-color: #17a2b8; border: none; }
    .btn-info:hover { background-color: #138496; }
    .badge { font-weight: 500; padding: 0.4em 0.6em; min-width: 70px; text-align: center; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookmarkButtons = document.querySelectorAll('.ajax-bookmark-btn');

    bookmarkButtons.forEach(button => {
        button.addEventListener('click', function() {
            const eventId = this.dataset.id;
            const starIcon = this.querySelector('.star-icon');
            const url = `/events/${eventId}/bookmark`;

            this.style.pointerEvents = 'none';

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('通信エラー');
                return response.json();
            })
            .then(data => {
                if (data.status === 'bookmarked') {
                    starIcon.textContent = '★';
                    starIcon.style.color = '#f1c40f';
                } else {
                    starIcon.textContent = '☆';
                    starIcon.style.color = '#ccc';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            })
            .finally(() => {
                this.style.pointerEvents = 'auto';
            });
        });
    });
});
</script>
@endsection