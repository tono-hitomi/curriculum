@extends('layouts.app')

@section('content')
<div class="container">
    {{-- ヘッダーエリア：ユーザー情報を強調 --}}
    <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-3">
        <div>
            <h2 class="mb-0">マイページ</h2>
            <p class="text-muted mb-0">おかえりなさい、{{ Auth::user()->name }} さん</p>
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

            {{-- 参加イベント一覧 --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white font-weight-bold">
                    <i class="fas fa-calendar-check text-primary"></i> 参加予定のイベント
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($participatingEvents as $event)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="font-weight-bold text-truncate" style="max-width: 70%;">{{ $event->title }}</span>
                            <a href="{{ route('events.show', $event->id) }}?from=mypage" class="btn btn-sm btn-outline-primary px-3">詳細を見る</a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted py-4 text-center">参加予定のイベントはありません。</li>
                    @endforelse
                </ul>
            </div>

            {{-- 主催イベント一覧 --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center font-weight-bold">
                    <span><i class="fas fa-bullhorn text-success"></i> 主催しているイベント</span>
                    <a href="{{ route('events.create') }}" class="btn btn-sm btn-success">+ 新規作成</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($myEvents as $event)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="font-weight-bold text-truncate" style="max-width: 70%;">{{ $event->title }}</span>
                                <a href="{{ route('events.show', $event->id) }}?from=mypage" class="btn btn-sm btn-outline-success px-3">管理・詳細</a>
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
                            <small class="text-muted">参加</small>
                        </div>
                        <div class="col-6">
                            <div class="h3 mb-0 font-weight-bold">{{ $myEvents->count() }}</div>
                            <small class="text-muted">主催</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ブックマーク一覧 --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center font-weight-bold">
                    <span><i class="fas fa-star text-warning"></i> ブックマーク</span>
                    {{-- ★ここに追加しました --}}
                    <a href="{{ route('bookmarks.index') }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;">
                        一覧へ
                    </a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($bookmarkedEvents as $event)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <span class="small text-truncate" style="max-width: 140px;">{{ $event->title }}</span>
                            <a href="{{ route('events.show', $event->id) }}?from=mypage" class="btn btn-sm btn-link text-decoration-none p-0" style="font-size: 0.8rem;">詳細 <i class="fas fa-chevron-right"></i></a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small py-3 text-center">ブックマークはありません。</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection