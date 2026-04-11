@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h2>イベント詳細</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10"> 
            <div class="card shadow-sm p-4">
                <div class="row">
                    {{-- 左側：イベント情報 --}}
                    <div class="col-md-7">
                        <div class="mb-3">
                            <span class="badge badge-primary mb-1">主催者</span>
                            <div class="h5 border-bottom pb-2">{{ $event->user->name }}</div>
                        </div>

                        <div class="mb-3">
                            <span class="badge badge-info mb-1">イベントタイトル</span>
                            <div class="h4 font-weight-bold">{{ $event->title }}</div>
                        </div>

                        <div class="mb-3 mt-4">
                            <label class="font-weight-bold text-muted small">詳細内容</label>
                            <div class="p-3 border rounded bg-light" style="min-height: 250px; white-space: pre-wrap;">{{ $event->comment }}</div>
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
                        <div class="alert alert-secondary py-3">
                            <p class="font-weight-bold mb-3">あなたはのこのイベントの主催者です</p>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-success px-5 mx-2">
                                    <i class="fas fa-edit"></i> 編集する
                                </a>
                                
                                <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('本当にこのイベントを削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger px-5 mx-2">
                                        <i class="fas fa-trash"></i> 削除する
                                    </button>
                                </form>
                            </div>
                        </div>

                    {{-- 他人が主催者の場合 --}}
                    @else
                        @if($event->isJoined(Auth::id())) 
                            <button class="btn btn-secondary btn-lg px-5 shadow-sm" disabled>
                                <i class="fas fa-check-circle"></i> 参加申込済み
                            </button>
                        @else
                            <a href="{{ route('events.apply', $event->id) }}" class="btn btn-warning btn-lg px-5 text-white shadow" style="background-color: #d35400; font-weight: bold;">
                                参加を申し込む
                            </a>
                        @endif
                    @endif

                    <div class="text-right mt-4">
                        <a href="{{ route('events.report', $event->id) }}" class="text-danger small">
                            <i class="fas fa-flag"></i> 規約違反を報告する
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 d-flex justify-content-between">
                @if(request()->query('from') === 'mypage')
                    <a href="{{ route('mypage') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> マイページに戻る
                    </a>
                @else
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> 一覧へ戻る
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection