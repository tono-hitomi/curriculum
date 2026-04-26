@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 font-weight-bold">参加申込管理</h2>

    @if (session('status'))
        <div class="alert alert-success shadow-sm mb-4">{{ session('status') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover text-center mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th>ID</th>
                        <th class="text-left">イベント名</th>
                        <th>主催者</th>
                        <th>開催日</th>
                        <th>申込数</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td>{{ $event->id }}</td>
                        <td class="text-left">
                            <a href="{{ route('events.show', $event->id) }}" class="font-weight-bold text-primary">
                                {{ Str::limit($event->title, 30) }}
                            </a>
                        </td>
                        <td>{{ $event->user->name ?? '不明' }}</td>
                        <td>{{ \Carbon\Carbon::parse($event->date)->format('Y/m/d') }}</td>
                        <td>
                            <span class="badge badge-pill badge-primary px-3 py-2">
                                {{ $event->users_count }} 名
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-info btn-sm px-3" 
                                    data-toggle="modal" data-target="#modal-event-{{ $event->id }}">
                                申込者を確認
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-muted py-5">現在、公開されているイベントはありません。</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ページネーション --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $events->links() }}
    </div>

    <div class="text-right mt-3 mb-5">
        <a href="/admin" class="btn btn-outline-secondary px-4 shadow-sm">
            <i class="fas fa-home"></i> メインへ戻る
        </a>
    </div>
</div>

@foreach($events as $event)
<div class="modal fade" id="modal-event-{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="label-{{ $event->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="label-{{ $event->id }}">
                    <i class="fas fa-users mr-2"></i>【{{ $event->title }}】申込者一覧
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" style="max-height: 60vh; overflow-y: auto;">
                <table class="table table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-top-0">ユーザー名</th>
                            <th class="border-top-0">メールアドレス</th>
                            <th class="border-top-0">申込時のコメント</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($event->users as $applicant)
                        <tr>
                            <td class="align-middle">{{ $applicant->name }}</td>
                            <td class="align-middle">{{ $applicant->email }}</td>
                            <td class="align-middle">
                                <div class="bg-white p-2 rounded border" style="font-size: 0.85rem; min-height: 40px;">
                                    {{ $applicant->pivot->comment ?? 'コメントなし' }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">申込者はまだいません。</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection