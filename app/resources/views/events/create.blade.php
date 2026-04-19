@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>新規イベント作成</h3>
        <a href="{{ route('mypage') }}" class="btn btn-outline-secondary">マイページに戻る</a>
    </div>

    <div class="card shadow-sm p-4">
        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                {{-- 左側：入力エリア --}}
                <div class="col-md-7">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">イベントタイトル <span class="badge bg-danger">必須</span></label>
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
                        <label class="font-weight-bold">開催日時 <span class="badge bg-danger">必須</span></label>
                        <input type="datetime-local" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                        @error('date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">イベント形式 <span class="badge bg-danger">必須</span></label>
                        <select name="format" class="form-control @error('format') is-invalid @enderror" required>
                            <option value="">選択してください</option>
                            <option value="Zoom" {{ old('format') == 'Zoom' ? 'selected' : '' }}>Zoom</option>
                            <option value="YouTube" {{ old('format') == 'YouTube' ? 'selected' : '' }}>YouTube</option>
                            <option value="対面" {{ old('format') == '対面' ? 'selected' : '' }}>対面</option>
                            <option value="その他" {{ old('format') == 'その他' ? 'selected' : '' }}>その他</option>
                        </select>
                        @error('format')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">定員数 <span class="badge bg-secondary">任意</span></label>
                        <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity') }}" placeholder="例：30">
                        <small class="text-muted">※無制限の場合は空欄にしてください</small>
                        @error('capacity')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">イベント詳細 <span class="badge bg-danger">必須</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="8" placeholder="イベントの内容や持ち物、スケジュールなどを入力してください" required>{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- 右側：画像アップロードエリア --}}
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="font-weight-bold">メイン画像 <span class="badge bg-danger">必須</span></label>
                        <div class="border rounded d-flex align-items-center justify-content-center flex-column @error('image') border-danger @enderror" style="height: 480px; background: #f8f9fa; border-style: dashed !important;">
                            <i class="fas fa-image fa-3x text-muted mb-3"></i>
                            <input type="file" name="image" class="form-control-file p-3 @error('image') is-invalid @enderror" required>
                            @error('image')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-2">推奨：正方形または横長画像</small>
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