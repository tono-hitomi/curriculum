@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <div class="alert alert-secondary py-5 shadow-sm">
        <i class="fas fa-exclamation-triangle fa-3x mb-3 text-muted"></i>
        <h2 class="h4">このイベントは公開が停止されました</h2>
        <p class="text-muted">主催者のアカウント状況、または規約違反により現在は閲覧できません。</p>
        <hr>
        <a href="{{ route('home') }}" class="btn btn-primary px-4">イベント一覧に戻る</a>
    </div>
</div>
@endsection