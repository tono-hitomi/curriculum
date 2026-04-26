@extends('layouts.app')

@section('content')
<style>
    /* 警告画面専用の背景調整 */
    body { background-color: #f4f7f6; }
    .card { border-radius: 12px; }
    .card-header { border-radius: 12px 12px 0 0 !important; }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            {{-- パンくずリスト　いらんかも --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">管理メイン</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">ユーザー管理</a></li>
                    <li class="breadcrumb-item active">利用停止確認</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-lg">
                <div class="card-header bg-danger text-white fw-bold py-3">
                    <i class="fas fa-exclamation-triangle me-2"></i> ユーザー利用停止の確認
                </div>
                <div class="card-body text-center py-5">
                    <p class="mb-4 text-muted">
                        以下のユーザーを利用停止します。<br>
                        停止されたユーザーはログインができなくなります。
                    </p>
                    
                    <div class="bg-light p-4 rounded-3 mb-4 text-start border">
                        <div class="mb-2">
                            <small class="text-muted d-block">ユーザー名</small>
                            <span class="h5 fw-bold">{{ $user->name }}</span>
                        </div>
                        <div>
                            <small class="text-muted d-block">メールアドレス</small>
                            <span class="text-dark">{{ $user->email }}</span>
                        </div>
                    </div>

                    <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                        @csrf
                        <div class="d-grid d-md-block gap-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-5 me-md-2">
                                キャンセル
                            </a>
                            <button type="submit" class="btn btn-danger px-5 shadow-sm">
                                利用停止を実行する
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection