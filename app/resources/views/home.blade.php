@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            {{-- 成功メッセージの表示 --}}
            @if (session('status'))
                <div class="alert alert-success shadow-sm border-0" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            {{-- 検索フォーム --}}
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
                            <label class="sr-only">開始日</label>
                            <input type="date" name="from_date" class="form-control mr-1" value="{{ request('from_date') }}">
                            <span class="mr-1">〜</span>
                            <label class="sr-only">終了日</label>
                            <input type="date" name="to_date" class="form-control mr-2" value="{{ request('to_date') }}">
                        </div>

                        <div class="form-group mb-2">
                            <select name="format" class="form-control mr-2">
                                <option value="">配信形式を選択</option>
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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 font-weight-bold text-muted">開催イベント一覧</h5>
                @auth
                    <a href="{{ route('mypage') }}" class="btn btn-sm btn-outline-primary px-3 shadow-sm bg-white">
                        <i class="fas fa-user-circle"></i> マイページへ
                    </a>
                @endauth
            </div>

            <div class="row">
                @forelse ($events as $event)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0 event-card overflow-hidden">
                            
                            {{-- 形式バッジとブックマークを画像の外に配置 --}}
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center px-3 pt-3 pb-0">
                                <div>
                                    @if($event->format === 'Zoom')
                                        <span class="badge badge-info text-white px-2 py-1 small">Zoom</span>
                                    @elseif($event->format === 'YouTube')
                                        <span class="badge badge-danger px-2 py-1 small">YouTube</span>
                                    @elseif($event->format === '対面')
                                        <span class="badge badge-success px-2 py-1 small">対面</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1 small">その他</span>
                                    @endif
                                </div>

                                @auth
                                    <button type="button" class="ajax-bookmark-btn" data-id="{{ $event->id }}" style="border: none; background: none; outline: none; padding: 0;">
                                        <span class="star-icon" style="color: {{ Auth::user()->bookmarks()->where('event_id', $event->id)->exists() ? '#f1c40f' : '#ccc' }}; font-size: 1.5rem;">
                                            {{ Auth::user()->bookmarks()->where('event_id', $event->id)->exists() ? '★' : '☆' }}
                                        </span>
                                    </button>
                                @else
                                    <span style="color: #eee; font-size: 1.5rem;">☆</span>
                                @endauth
                            </div>

                            {{-- 画像エリア --}}
                            <div class="image-container" style="width: 100%; height: 200px; background-color: #f8f9fa; margin-top: 10px;">
                                <a href="{{ route('events.show', $event->id) }}">
                                    @if($event->image)
                                        <img src="{{ asset('storage/event_images/' . $event->image) }}" alt="event image" style="width: 100%; height: 100%; object-fit: contain; background-color: #efefef;">
                                    @else
                                        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                            <i class="far fa-image fa-2x mb-1"></i>
                                            <small style="font-size: 0.6rem;">NO IMAGE</small>
                                        </div>
                                    @endif
                                </a>
                            </div>

                            {{-- 情報エリア --}}
                            <div class="card-body">
                                <h5 class="card-title font-weight-bold mb-2">
                                    <a href="{{ route('events.show', $event->id) }}" class="text-dark text-decoration-none">{{ $event->title }}</a>
                                </h5>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="small text-muted">
                                        <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($event->date)->format('Y/m/d') }}
                                        <span class="mx-1">|</span>
                                        <i class="far fa-user-circle"></i> {{ $event->user->name ?? '不明' }}
                                    </div>
                                    <div class="small">
                                        <span class="font-weight-bold {{ (!is_null($event->capacity) && $event->users->count() >= $event->capacity) ? 'text-danger' : 'text-primary' }}">
                                            {{ $event->users->count() }}
                                        </span>
                                        <span class="text-muted">/{{ $event->capacity ?? '∞' }} 名</span>
                                    </div>
                                </div>

                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary btn-block shadow-sm py-2">詳細を見る</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="fas fa-calendar-times fa-3x mb-3 d-block opacity-50"></i>
                        該当するイベントは見つかりませんでした。
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>

<style>
    .event-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 12px !important;
    }
    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.1) !important;
    }
    .image-container {
        overflow: hidden;
    }
    .image-container img {
        transition: transform 0.5s;
    }
    .event-card:hover .image-container img {
        transform: scale(1.05);
    }
    .ajax-bookmark-btn {
        transition: transform 0.2s;
    }
    .ajax-bookmark-btn:hover {
        transform: scale(1.2);
    }
    .badge {
        font-weight: 600;
        letter-spacing: 0.5px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookmarkButtons = document.querySelectorAll('.ajax-bookmark-btn');

    bookmarkButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
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
                    starIcon.style.color = '#ccc'; {{-- 背景白 --}}
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                this.style.pointerEvents = 'auto';
            });
        });
    });
});
</script>
@endsection