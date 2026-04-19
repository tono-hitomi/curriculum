@extends('layouts.app')

@section('content')
<style>
    /* 管理者画面専用のスタイル */
    body { background-color: #f4f7f6; }
    .card-header { font-weight: bold; background-color: #e9ecef; }
    .table-scroll { max-height: 400px; overflow-y: auto; }
    .navbar-admin { border-radius: 5px; }
    .btn-export-head { font-size: 0.7rem; padding: 2px 8px; }
</style>

<div class="container-fluid px-4">
    {{-- 管理者用のサブナビゲーション --}}
    <nav class="navbar navbar-dark bg-dark mb-4 navbar-admin shadow-sm">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">メイン画面（管理者専用）</span>
        </div>
    </nav>

    <div class="row">
        {{-- 全ユーザー（参加・主催件数） --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>全ユーザー</span>
                    <a href="{{ route('admin.users.export') }}" class="btn btn-outline-primary btn-sm btn-export-head shadow-sm">
                        <i class="fas fa-file-csv"></i> CSV
                    </a>
                </div>
                <div class="card-body p-0 table-scroll">
                    <table class="table table-hover mb-0" style="font-size: 0.8rem;">
                        <thead class="table-light">
                            <tr>
                                <th>名</th>
                                <th>参加</th>
                                <th>主催</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->applications_count }}件</td>
                                <td>{{ $user->events_count }}件</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                        ユーザー詳細、編集
                    </a>
                </div>
            </div>
        </div>

        {{-- 全イベント（違反報告数TOP10） --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>全イベント（報告TOP10）</span>
                    <a href="{{ route('admin.events.export') }}" class="btn btn-outline-primary btn-sm btn-export-head shadow-sm">
                        <i class="fas fa-file-csv"></i> CSV
                    </a>
                </div>
                <div class="card-body p-0 table-scroll">
                    <table class="table table-hover mb-0" style="font-size: 0.8rem;">
                        <thead class="table-light">
                            <tr>
                                <th>イベント名</th>
                                <th>報告数</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                            <tr>
                                <td>{{ $event->title }}</td>
                                <td class="text-danger fw-bold">{{ $event->report_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white text-center">
                    <div class="d-grid gap-2">
                        {{-- 違反報告一覧へのリンク --}}
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-danger btn-sm shadow-sm mb-2 w-100">
                            <i class="fas fa-exclamation-triangle"></i> 違反報告一覧を確認する
                        </a>
                        {{-- 全イベント管理へのリンク --}}
                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary btn-sm shadow-sm w-100">
                            全イベントを表示
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 全参加申込（参加者／イベント） --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>全参加申込</span>
                    <a href="{{ route('admin.applications.export') }}" class="btn btn-outline-primary btn-sm btn-export-head shadow-sm">
                        <i class="fas fa-file-csv"></i> CSV
                    </a>
                </div>
                <div class="card-body p-0 table-scroll">
                    <table class="table table-hover mb-0" style="font-size: 0.8rem;">
                        <thead class="table-light">
                            <tr>
                                <th>参加者</th>
                                <th>イベント名</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $app)
                            <tr>
                                <td>{{ $app->user->name }}<br><small class="text-muted">{{ $app->user->email }}</small></td>
                                <td>{{ $app->event->title }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection