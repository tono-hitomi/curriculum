@extends('layouts.app')

@section('content')
<div class="container">
    {{-- ヘッダーエリア：ユーザー情報 --}}
    <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-3">
        <div>
            <h2 class="mb-0 font-weight-bold">マイページ</h2>
            <p class="text-muted mb-0">{{ Auth::user()->name }} さん</p>
        </div>
        <div>
            <a href="{{ route('profile') }}" class="btn btn-info text-white shadow-sm mr-2">
                <i class="fas fa-user-cog"></i> プロフィール設定
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-home"></i> メインへ
            </a>
        </div>
    </div>

    <div class="row">
        {{-- 左側：メインエリア（参加・主催イベント） --}}
        <div class="col-md-8">
            {{-- 成功メッセージの表示 --}}
            @if (session('status'))
                <div class="alert alert-success shadow-sm mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            {{-- 参加予定イベント --}}
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white font-weight-bold">
                    <i class="fas fa-calendar-check text-primary mr-1"></i> 参加予定のイベント
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($participatingEvents as $event)
                            <li class="list-group-item py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1 mr-3">
                                        {{-- 形式バッジ --}}
                                        @if($event->format === 'Zoom')
                                            <span class="badge badge-info text-white mb-1">Zoom</span>
                                        @elseif($event->format === 'YouTube')
                                            <span class="badge badge-danger mb-1">YouTube</span>
                                        @elseif($event->format === '対面')
                                            <span class="badge badge-success mb-1">対面</span>
                                        @else
                                            <span class="badge badge-secondary mb-1">その他</span>
                                        @endif
                                        
                                        <div class="font-weight-bold text-dark h6 mb-1">{{ $event->title }}</div>
                                        
                                        <div class="text-muted small">
                                            <span class="mr-3"><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($event->date)->format('Y/m/d H:i') }}</span>
                                            <span><i class="fas fa-users"></i> {{ $event->users->count() }} / {{ $event->capacity ?? '∞' }} 名</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <a href="{{ route('events.show', $event->id) }}?from=mypage" class="btn btn-sm btn-outline-primary px-3 shadow-sm">詳細</a>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted py-4 text-center">参加予定のイベントはありません。</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- 主催イベント一覧 --}}
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center font-weight-bold">
                    <span><i class="fas fa-bullhorn text-success mr-1"></i> 主催しているイベント</span>
                    <a href="{{ route('events.create') }}" class="btn btn-sm btn-success shadow-sm">+ 新規作成</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($myEvents as $event)
                            <li class="list-group-item py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1 mr-3">
                                        {{-- 形式バッジ --}}
                                        @if($event->format === 'Zoom')
                                            <span class="badge badge-info text-white mb-1">Zoom</span>
                                        @elseif($event->format === 'YouTube')
                                            <span class="badge badge-danger mb-1">YouTube</span>
                                        @elseif($event->format === '対面')
                                            <span class="badge badge-success mb-1">対面</span>
                                        @else
                                            <span class="badge badge-secondary mb-1">その他</span>
                                        @endif

                                        <div class="font-weight-bold text-dark h6 mb-1">{{ $event->title }}</div>
                                        
                                        <div class="text-muted small">
                                            <span class="mr-3"><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($event->date)->format('Y/m/d H:i') }}</span>
                                            <span><i class="fas fa-users"></i> {{ $event->users->count() }} / {{ $event->capacity ?? '∞' }} 名</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <a href="{{ route('events.show', $event->id) }}?from=mypage" class="btn btn-sm btn-outline-success px-3 shadow-sm">管理</a>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted py-4 text-center">主催しているイベントはありません。</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- 右側：サイドバー（統計・ブックマーク） --}}
        <div class="col-md-4">
            {{-- 簡易統計 --}}
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-dark text-white font-weight-bold">
                    活動状況
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 border-right">
                            <div class="h3 mb-0 font-weight-bold">{{ $participatingEvents->count() }}</div>
                            <small class="text-muted">参加予定</small>
                        </div>
                        <div class="col-6">
                            <div class="h3 mb-0 font-weight-bold">{{ $myEvents->count() }}</div>
                            <small class="text-muted">主催</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ブックマーク一覧 --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center font-weight-bold">
                    <span><i class="fas fa-star text-warning mr-1"></i> ブックマーク</span>
                    <a href="{{ route('bookmarks.index') }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;">
                        一覧へ
                    </a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($bookmarkedEvents as $event)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <span class="small text-truncate font-weight-bold" style="max-width: 140px;">{{ $event->title }}</span>
                            <a href="{{ route('events.show', $event->id) }}?from=mypage" class="btn btn-sm btn-link text-decoration-none p-0" style="font-size: 0.8rem;">
                                詳細 <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small py-3 text-center">ブックマークはありません。</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .card { border-radius: 10px; }
    .badge { padding: 0.4em 0.7em; min-width: 60px; }
    .list-group-item:hover { background-color: #fcfcfc; }
</style>
@endsection