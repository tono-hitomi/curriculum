@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>プロフィール編集</h2>
                <a href="{{ route('profile') }}" class="btn btn-outline-secondary">キャンセル</a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- ユーザー名 --}}
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">ユーザー名</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- メールアドレス --}}
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">メールアドレス</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- プロフィール画像 --}}
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">プロフィール画像</label>
                            <div class="border rounded p-3 bg-light text-center mb-2">
                                @if($user->image)
                                    <img src="{{ asset('storage/profile_images/' . $user->image) }}" class="rounded-circle shadow-sm mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="mb-3">
                                        <i class="fas fa-user-circle fa-4x text-muted"></i>
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control-file">
                                <small class="text-muted">新しい画像をアップロードすると現在の画像が差し替わります</small>
                            </div>
                        </div>

                        {{-- 自己紹介 --}}
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">自己紹介</label>
                            <textarea name="introduction" class="form-control @error('introduction') is-invalid @enderror" rows="5" placeholder="趣味や関心のあるイベントについて教えてください">{{ old('introduction', $user->introduction) }}</textarea>
                            @error('introduction')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="text-center border-top pt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                変更を保存する
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- 退会への導線 --}}
            <div class="text-center mt-5">
                <a href="{{ route('profile.delete.confirm') }}" class="text-danger small">退会をご希望の方はこちら</a>
            </div>
        </div>
    </div>
</div>
@endsection