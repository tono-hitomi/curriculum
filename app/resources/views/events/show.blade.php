@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h2 class="font-weight-bold text-dark">イベント詳細</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10"> 
            
            {{-- ステータスメッセージ --}}
            @if (session('status'))
                <div class="alert alert-success shadow-sm mb-4">{{ session('status') }}</div>
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

                        <div class="mb-3 mt-4">
                            <label class="font-weight-bold text-muted small">詳細内容</label>
                            <div class="p-3 border rounded bg-light shadow-inner" style="min-height: 250px; white-space: pre-wrap;">{{ $event->comment }}</div>
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
                    </div>
                </div>

                <hr class="my-4">

                <div class="text-center">
                    {{-- 自分が主催者の場合 --}}
                    @if($event->user_id === Auth::id())
                        <div class="alert alert-secondary py-4 shadow-sm">
                            <p class="font-weight-bold mb-3"><i class="fas fa-user-shield"></i> あなたはこのイベントの主催者です</p>
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

                    {{-- 他人が主催者の場合 --}}
                    @else
                        @if($event->users->contains(Auth::id()))
                            {{-- 既に参加している場合 --}}
                            <form action="{{ route('events.cancel', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-lg px-5 shadow-sm">
                                    参加をキャンセルする
                                </button>
                            </form>
                        @else
                            {{-- まだ参加していない場合：申込入力画面（apply）へ遷移 --}}
                            <a href="{{ route('events.apply', $event->id) }}" class="btn btn-primary btn-lg px-5 shadow-sm">
                                参加を申し込む
                            </a>
                        @endif
                    @endif

                    <div class="text-right mt-4">
                        <a href="{{ route('events.report', $event->id) }}" class="text-danger small font-weight-bold">
                            <i class="fas fa-flag"></i> 規約違反を報告する
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 d-flex justify-content-between">
                @if(request()->query('from') === 'mypage')
                    <a href="{{ route('mypage') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-arrow-left"></i> マイページに戻る
                    </a>
                @else
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-arrow-left"></i> 一覧へ戻る
                    </a>
                @endif
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