@extends('layouts.app')

@section('content')
<div class="container">
    {{-- パンくずリスト --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">管理メイン</a></li>
            <li class="breadcrumb-item active" aria-current="page">ユーザー管理</li>
        </ol>
    </nav>

    <h2 class="mb-4">全ユーザー詳細一覧（管理者）</h2>
    
    <div class="table-responsive">
        <table class="table table-bordered text-center shadow-sm">
            <thead>
                <tr class="table-secondary text-muted">
                    <th>ID</th>
                    <th>ユーザー名</th>
                    <th>メールアドレス</th>
                    <th>参加数</th>
                    <th>主催数</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="align-middle">{{ $user->id }}</td>
                    <td class="align-middle text-start ps-3 font-weight-bold">{{ $user->name }}</td>
                    <td class="align-middle text-muted">{{ $user->email }}</td>
                    <td class="align-middle">{{ $user->applications_count }}件</td>
                    <td class="align-middle">{{ $user->events_count }}件</td>
                    <td class="align-middle">
                        <a href="{{ route('admin.users.confirm_suspend', $user->id) }}" class="btn btn-danger btn-sm px-3" style="width: 100px;">
                            利用停止
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ページネーション --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links() }}
    </div>

    {{-- メインへ戻る（イベント詳細とデザイン・位置を統一） --}}
    <div class="text-right mt-3">
        <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary px-4">メインへ</a>
    </div>
</div>

<style>
    /* 共通デザインのための微調整 */
    .table th { font-size: 0.9rem; }
    .text-start { text-align: left !important; }
    .breadcrumb-item + .breadcrumb-item::before { content: ">"; }
</style>
@endsection