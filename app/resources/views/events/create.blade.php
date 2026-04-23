@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="font-weight-bold">新規イベント作成</h3>
        <a href="{{ route('mypage') }}" class="btn btn-outline-secondary px-3">
            <i class="fas fa-arrow-left"></i> マイページに戻る
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- 左側：入力エリア --}}
                    <div class="col-md-7">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">イベントタイトル <span class="badge bg-danger text-white">必須</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="例：Laravel勉強会" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="font-weight-bold text-muted small">主催者</label>
                            <p class="form-control-plaintext border-bottom pl-2 font-weight-bold">{{ Auth::user()->name }}</p>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">開催日時 <span class="badge bg-danger text-white">必須</span></label>
                            <input type="datetime-local" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                            @error('date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">イベント形式 <span class="badge bg-danger text-white">必須</span></label>
                            <select name="format" class="form-control @error('format') is-invalid @enderror" required>
                                <option value="">選択してください</option>
                                {{-- データベースの文字列保存に合わせてvalueを設定 --}}
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
                            <label class="font-weight-bold">定員数 <span class="badge bg-secondary text-white">任意</span></label>
                            <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity') }}" placeholder="例：30">
                            <small class="text-muted">※無制限の場合は空欄にしてください</small>
                            @error('capacity')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">イベント詳細 <span class="badge bg-danger text-white">必須</span></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="8" placeholder="イベントの内容やスケジュールなどを詳しく入力してください" required>{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- 右側：画像アップロードエリア --}}
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="font-weight-bold">メイン画像 <span class="badge bg-danger text-white">必須</span></label>
                            <div class="border rounded d-flex align-items-center justify-content-center flex-column @error('image') border-danger @enderror" style="height: 100%; min-height: 400px; background: #f8f9fa; border-style: dashed !important; border-width: 2px;">
                                <i class="fas fa-image fa-4x text-muted mb-3"></i>
                                <div class="px-4 w-100">
                                    <input type="file" name="image" id="imageInput" class="form-control-file @error('image') is-invalid @enderror" required>
                                </div>
                                @error('image')
                                    <div class="text-danger small mt-2 font-weight-bold">{{ $message }}</div>
                                @enderror
                                <small class="text-muted mt-3">推奨：正方形または横長画像</small>
                                
                                {{-- プレビュー表示用エリア（任意） --}}
                                <div id="imagePreview" class="mt-3 w-100 px-4" style="display:none;">
                                    <img id="previewImg" src="" alt="Preview" style="max-width: 100%; height: auto; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                        <i class="fas fa-paper-plane mr-2"></i> イベントを公開する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// 画像を選択した際に簡易的なプレビューを表示するスクリプト
document.getElementById('imageInput').addEventListener('change', function(e) {
    const reader = new FileReader();
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    reader.onload = function(e) {
        previewImg.src = e.target.result;
        preview.style.display = 'block';
    }
    
    if (this.files[0]) {
        reader.readAsDataURL(this.files[0]);
    }
});
</script>

<style>
    .card { border-radius: 15px; }
    .badge { font-size: 0.75rem; vertical-align: middle; }
    .form-control:focus { border-color: #3490dc; box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.25); }
</style>
@endsection