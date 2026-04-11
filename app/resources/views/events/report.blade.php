@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h2 class="text-danger"><i class="fas fa-exclamation-triangle"></i> 違反報告</h2>
        <p class="text-muted">不適切なコンテンツを報告し、コミュニティの安全にご協力ください。</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('events.report.store', $event->id) }}" method="POST">
                @csrf
                <div class="card shadow-sm p-4">
                    <div class="mb-3">
                        <label class="small text-muted mb-1">報告対象の主催者</label>
                        <div class="border rounded p-2 bg-light text-center">
                            {{ $event->user->name }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted mb-1">報告対象のイベント</label>
                        <div class="border rounded p-2 bg-light text-center">
                            {{ $event->title }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="report_comment" class="font-weight-bold">報告理由・内容</label>
                        <textarea name="report_comment" id="report_comment" class="form-control" rows="8" placeholder="具体的な問題点を入力してください" required></textarea>
                        <small class="form-text text-muted">※運営事務局で内容を確認させていただきます。</small>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-outline-danger btn-lg px-5">
                            この内容で報告を送信する
                        </button>
                    </div>
                </div>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('events.show', $event->id) }}" class="text-secondary">
                    <i class="fas fa-arrow-left"></i> 詳細画面に戻る
                </a>
            </div>
        </div>
    </div>
</div>
@endsection