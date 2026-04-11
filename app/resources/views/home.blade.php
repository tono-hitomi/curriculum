@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            {{-- 検索 --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white font-weight-bold text-muted">
                    <i class="fas fa-search"></i> イベントを絞り込む
                </div>
                <div class="card-body">
                    <form action="{{ route('home') }}" method="GET" class="form-inline justify-content-center">
                        <div class="form-group mb-0">
                            <input type="text" name="keyword" class="form-control mr-2" placeholder="キーワード" value="{{ request('keyword') }}">
                        </div>

                        <div class="form-group mb-0">
                            <input type="date" name="date" class="form-control mr-2" value="{{ request('date') }}">
                        </div>

                        <div class="form-group mb-0">
                            <select name="format" class="form-control mr-2">
                                <option value="">配信形式を選択</option>
                                <option value="0" {{ request('format') === '0' ? 'selected' : '' }}>Zoom</option>
                                <option value="1" {{ request('format') === '1' ? 'selected' : '' }}>YouTube</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary px-4">検索</button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary ml-2">クリア</a>
                    </form>
                </div>
            </div>

            {{-- メイン一覧 --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold">開催イベント一覧</h5>
                    <a href="{{ route('mypage') }}" class="btn btn-sm btn-outline-primary">マイページへ</a>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 30%" class="border-top-0 pl-4">イベント名</th>
                                    <th style="width: 40%" class="border-top-0">主催者</th>
                                    <th style="width: 30%" class="border-top-0 text-right pr-4">アクション</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $event)
                                    <tr>
                                        <td class="align-middle pl-4 font-weight-bold">
                                            {{ $event->title }}
                                        </td>
                                        <td class="align-middle text-muted">
                                            <i class="far fa-user-circle"></i> {{ $event->user->name }}
                                        </td>
                                        <td class="align-middle text-right pr-4">
                                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-info text-white px-3 shadow-sm">詳細</a>

                                            {{-- ブックマークボタン --}}
                                            <form action="{{ route('events.bookmark', $event->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm bookmark-btn" style="border: none; background: none; outline: none; transition: transform 0.2s;">
                                                    @if(Auth::user()->bookmarks()->where('event_id', $event->id)->exists())
                                                        <span style="color: #f1c40f; font-size: 1.3rem;">★</span>
                                                    @else
                                                        <span style="color: #ccc; font-size: 1.3rem;">☆</span>
                                                    @endif
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
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
    /* ブックマークボタンにマウスを乗せた時に少し大きくする */
    .bookmark-btn:hover {
        transform: scale(1.2);
    }
</style>
@endsection