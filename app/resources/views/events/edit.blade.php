@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>主催イベント編集</h2>
        <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-secondary">詳細に戻る</a>
    </div>

    <div class="card shadow-sm p-4">
        <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <div class="row">
                {{-- 左側：入力エリア --}}
                <div class="col-md-7">
                    {{-- タイトル：$event->title を表示 --}}
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">タイトル編集</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $event->title) }}" required>
                        @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        {{-- 開催日：$event->date を yyyy-mm-dd 形式で表示 --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">開催日</label>
                                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" 
                                       value="{{ old('date', \Carbon\Carbon::parse($event->date)->format('Y-m-d')) }}" required>
                                @error('date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        {{-- 定員：$event->capacity を表示 --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">定員（人数）</label>
                                <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" 
                                       value="{{ old('capacity', $event->capacity) }}" min="1">
                                @error('capacity') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 形式：$event->format を表示 --}}
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">形式</label>
                        <input type="text" name="format" class="form-control @error('format') is-invalid @enderror" 
                               value="{{ old('format', $event->format) }}" required>
                        @error('format') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- 詳細：$event->comment を表示 --}}
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">詳細編集</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="6" required>{{ old('description', $event->comment) }}</textarea>
                        @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- 右側：画像エリア --}}
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="font-weight-bold">画像編集</label>
                        <div class="border rounded p-3 text-center bg-light" style="min-height: 330px;">
                            @if($event->image)
                                <div class="mb-3">
                                    <p class="small text-muted mb-1">現在の画像</p>
                                    <img src="{{ asset('storage/event_images/' . $event->image) }}" 
                                         class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                </div>
                            @else
                                <div class="mb-3 py-4">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                    <p class="small text-muted">画像は未登録です</p>
                                </div>
                            @endif
                            <hr>
                            <p class="small text-muted font-weight-bold">画像を差し替える</p>
                            <input type="file" name="image" class="form-control-file">
                            <p class="x-small text-danger mt-2">※変更する場合のみ選択してください</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow">変更を保存する</button>
            </div>
        </form>
    </div>
</div>
@endsection