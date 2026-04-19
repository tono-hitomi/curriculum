@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h2>ブックマーク一覧</h2>
    </div>

    <div class="card p-4">
        <div class="text-center h5 mb-4 border-bottom pb-2">ヘッダー</div>

        @foreach($bookmarkedEvents as $event)
            <div class="d-flex align-items-center mb-2 border p-2 rounded justify-content-between">
                <div class="d-flex flex-grow-1">
                    <a href="{{ route('events.show', $event->id) }}" class="text-primary mr-5">
                        {{ $event->title }}
                    </a>
                    <span class="text-primary">{{ $event->user->name }}</span>
                </div>

                {{-- ③ 削除ボタン --}}
                <form action="{{ route('bookmarks.destroy', $event->id) }}" method="POST" onsubmit="return confirm('削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-4">削除</button>
                </form>
            </div>
        @endforeach

        {{-- ① ページ番号 --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $bookmarkedEvents->links() }}
        </div>

        {{-- ② マイページへ戻るリンク --}}
        <div class="text-center mt-4">
            <a href="{{ route('mypage') }}" class="text-dark" style="text-decoration: underline;">マイページへ</a>
        </div>
    </div>
</div>
@endsection