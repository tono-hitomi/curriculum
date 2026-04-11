@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h3>退会処理</h3>
    <div class="card mt-4 p-4">
    <form action="{{ route('profile.delete') }}" method="POST">
        @csrf
        @method('DELETE')

        <p class="mb-4">
            ユーザーIDとパスワードを入力してください。
        </p>

        <div class="form-group mt-3">
            <label>ユーザー名</label>
            <input type="text" name="name" class="form-control w-50 mx-auto" required placeholder="ユーザー名を入力">
        </div>

        <div class="form-group mt-3">
            <label>パスワード</label>
            <input type="password" name="password" class="form-control w-50 mx-auto" required placeholder="パスワードを入力">
        </div>

        @if(session('error'))
            <p class="text-danger mt-3">{{ session('error') }}</p>
        @endif

        <div class="mt-4">
    <button type="submit" 
            class="btn btn-danger px-4" 
            onclick="return confirm('本当に退会しますか？この操作は取り消せません。');">
        退会を実行する
    </button>
    
    <a href="{{ route('profile') }}" class="btn btn-link">戻る</a>
</div>
    </form>
</div>
</div>
@endsection