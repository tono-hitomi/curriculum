@extends('layouts.app')

@section('content')
<div class="container">
    {{-- ヘッダーエリア：戻り先をパラメータで判定 --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            @php
                $from = request()->query('from');
            @endphp

            @if($from === 'admin_report')
                {{-- 違反報告一覧から来た場合 --}}
                <a href="/admin/reports" class="btn btn-outline-secondary shadow-sm px-4">
                    <i class="fas fa-arrow-left"></i> 報告一覧に戻る
                </a>
            @elseif($from === 'admin_events')
                {{-- 管理用のイベント一覧から来た場合 --}}
                <a href="/admin/events" class="btn btn-outline-secondary shadow-sm px-4">
                    <i class="fas fa-arrow-left"></i> 管理一覧に戻る
                </a>
            @elseif($from === 'mypage')
                {{-- マイページから来た場合 --}}
                <a href="{{ route('mypage') }}" class="btn btn-outline-secondary shadow-sm px-4">
                    <i class="fas fa-arrow-left"></i> マイページに戻る
                </a>
            @else
                {{-- それ以外（一般ホームなど） --}}
                <a href="{{ route('home') }}" class="btn btn-outline-secondary shadow-sm px-4">
                    <i class="fas fa-arrow-left"></i> 一覧に戻る
                </a>
            @endif
        </div>
        <h3 class="font-weight-bold text-dark mb-0">イベント詳細</h3>
        <div style="width: 85px;"></div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10"> 
            
            {{-- メッセージ表示 --}}
            @if (session('status'))
                <div class="alert alert-success shadow-sm mb-4">{{ session('status') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger shadow-sm mb-4">{{ session('error') }}</div>
            @endif

            <div class="card shadow-sm p-4 border-0">
                <div class="row">
                    {{-- 左側：イベント情報 --}}
                    <div class="col-md-7">
                        <div class="mb-3">
                            <span class="badge badge-primary px-3 py-2 mb-2">主催者</span>
                            <div class="h5 border-bottom pb-2 pl-1">{{ $event->user->name }}</div>
                        </div>

                        <div class="mb-3">
                            <span class="badge badge-info text-white px-3 py-2 mb-2">イベントタイトル</span>
                            <div class="h4 font-weight-bold pl-1">{{ $event->title }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="font-weight-bold text-muted small">開催予定日</label>
                                <div class="h5 pl-1">
                                    <i class="far fa-calendar-alt text-primary"></i> 
                                    {{ \Carbon\Carbon::parse($event->date)->format('Y/m/d H:i') }}
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="font-weight-bold text-muted small">開催形式</label>
                                <div class="h5 pl-1">
                                    @if($event->format === 'Zoom')
                                        <i class="fas fa-video text-info"></i> Zoom
                                    @elseif($event->format === 'YouTube')
                                        <i class="fab fa-youtube text-danger"></i> YouTube
                                    @elseif($event->format === '対面')
                                        <i class="fas fa-users text-success"></i> 対面
                                    @else
                                        <i class="fas fa-info-circle text-secondary"></i> {{ $event->format ?? 'その他' }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 mt-4">
                            <label class="font-weight-bold text-muted small">詳細内容</label>
                            <div class="p-3 border rounded bg-light shadow-inner" style="min-height: 250px; white-space: pre-wrap;">{{ $event->description ?? $event->comment }}</div>
                        </div>
                    </div>
                    
                    {{-- 右側：画像 --}}
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="font-weight-bold text-muted small">イベント画像</label>
                            <div class="border rounded d-flex align-items-center justify-content-center bg-white shadow-sm overflow-hidden" style="height: 350px;">
                                @if($event->image)
                                    <img src="{{ asset('storage/event_images/' . $event->image) }}" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%;">
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i><br>
                                        <span>No Image</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-3 text-right">
                            <span class="font-weight-bold text-muted">現在の参加人数: </span>
                            <span class="h5 font-weight-bold">{{ $event->users->count() }}</span>
                            @if(!is_null($event->capacity))
                                <span class="text-muted"> / {{ $event->capacity }} 名</span>
                            @endif
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="text-center">
                    {{-- 1. 管理者の場合 --}}
                    @if(Auth::user()->is_admin == 1)
                        <div class="alert alert-info py-3 border-0 shadow-sm">
                            <i class="fas fa-user-shield"></i> 管理者として詳細を閲覧中です
                        </div>

                    {{-- 2. 自分が主催者の場合 --}}
                    @elseif($event->user_id === Auth::id())
                        <div class="alert alert-secondary py-4 shadow-sm border-0">
                            <p class="font-weight-bold mb-3 text-dark"><i class="fas fa-user-shield text-primary"></i> あなたはこのイベントの主催者です</p>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-success px-5 mx-2 shadow-sm">
                                    <i class="fas fa-edit"></i> 編集する
                                </a>
                                <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('本当にこのイベントを削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger px-5 mx-2 shadow-sm">
                                        <i class="fas fa-trash"></i> 削除する
                                    </button>
                                </form>
                            </div>
                        </div>

                    {{-- 3. 一般ユーザーの場合 --}}
                    @else
                        @if($event->users->contains(Auth::id()))
                            <form action="{{ route('events.cancel', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-lg px-5 shadow-sm">
                                    参加をキャンセルする
                                </button>
                            </form>
                        @else
                            @if(!is_null($event->capacity) && $event->users->count() >= $event->capacity)
                                <button class="btn btn-secondary btn-lg px-5 shadow-sm" disabled style="cursor: not-allowed;">
                                    定員に達しました
                                </button>
                                <p class="text-danger small mt-2">※キャンセルが出るまで申し込みはできません</p>
                            @else
                                {{-- 申し込み画面へ行く時も from パラメータを引き継ぐ --}}
                                <a href="{{ route('events.apply', $event->id) }}?from={{ $from }}" class="btn btn-primary btn-lg px-5 shadow-sm">
                                    参加を申し込む
                                </a>
                            @endif
                        @endif

                        <div class="text-right mt-4">
                            <a href="{{ route('events.report', $event->id) }}" class="text-danger small font-weight-bold">
                                <i class="fas fa-flag"></i> 規約違反を報告する
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .badge { border-radius: 5px; }
    .shadow-inner { box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); }
    .card { border-radius: 15px; }
    .btn-lg { font-weight: bold; }
</style>
@endsection