@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>新規イベント作成</h3>
        <a href="{{ route('mypage') }}" class="btn btn-outline-secondary">マイページに戻る</a>
    </div>

    <div class="card shadow-sm p-4">
        {{-- ResourceControllerのstoreメソッドへ飛ばす--}}
        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                {{-- 左側：テキスト入力エリア --}}
                <div class="col-md-7">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">イベントタイトル</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="例：Laravel勉強会" required>
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="font-weight-bold">主催者</label>
                        <p class="form-control-plaintext border-bottom pl-2">{{ Auth::user()->name }}</p>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">イベント詳細</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="8" placeholder="イベントの内容や持ち物、スケジュールなどを入力してください" required>{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- 右側：画像アップロードエリア --}}
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="font-weight-bold">メイン画像</label>
                        <div class="border rounded d-flex align-items-center justify-content-center flex-column" style="height: 330px; background: #f8f9fa; border-style: dashed !important;">
                            <i class="fas fa-image fa-3x text-muted mb-3"></i>
                            <input type="file" name="image" class="form-control-file p-3">
                            <small class="text-muted">推奨：正方形または横長画像</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">イベントを公開する</button>
            </div>
        </form>
    </div>
</div>
@endsection