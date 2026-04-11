@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h2>申込画面</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('events.apply.store', $event->id) }}" method="POST">
                @csrf 
                <div class="card p-4 text-center">
                    <div class="border p-2 mb-3 text-primary">
                        主催者： {{ $event->user->name }}
                    </div>

                    <div class="border p-2 mb-3 text-primary">
                        イベント： {{ $event->title }}
                    </div>

                    <div class="form-group text-left">
                        <label for="comment" class="small text-muted">申込コメント</label>
                        <textarea name="comment" id="comment" class="form-control" rows="8" placeholder="意気込みや主催者へのメッセージを入力してください" required></textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning btn-lg px-5 text-white" style="background-color: #d35400;">
                            この内容で申し込む
                        </button>
                    </div>
                </div>
            </form>
            
            <div class="mt-3">
                <a href="{{ route('events.show', $event->id) }}" class="btn btn-secondary">詳細に戻る</a>
            </div>
        </div>
    </div>
</div>
@endsection