@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>プロフィール詳細</h2>
                <a href="{{ route('mypage') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> マイページに戻る
                </a>
            </div>

            <div class="card shadow-sm overflow-hidden">
                {{-- 上部のアクセントカラー --}}
                <div style="height: 100px; background-color: #3490dc;"></div>
                
                <div class="card-body pt-0">
                    <div class="row px-4">
                        {{-- 左側：アバター画像（上に少しはみ出すデザイン） --}}
                        <div class="col-md-4 text-center" style="margin-top: -50px;">
                            <div class="bg-white p-2 rounded-circle shadow-sm d-inline-block">
                                @if($user->image)
                                    <img src="{{ asset('storage/profile_images/' . $user->image) }}" 
                                         class="rounded-circle" 
                                         style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #fff;">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 150px; height: 150px; border: 3px solid #fff;">
                                        <i class="fas fa-user fa-5x text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-3">
                                <h4 class="font-weight-bold mb-0">{{ $user->name }}</h4>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-block mb-2">
                                    <i class="fas fa-edit"></i> プロフィール編集
                                </a>
                            </div>
                        </div>

                        {{-- 右側：自己紹介エリア --}}
                        <div class="col-md-8 pt-4">
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2 font-weight-bold text-muted">
                                    <i class="fas fa-info-circle"></i> 自己紹介
                                </h5>
                                <div class="p-3 bg-light rounded" style="min-height: 200px; white-space: pre-wrap;">
                                    {{ $user->introduction ?? '自己紹介はまだ登録されていません。' }}
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <a href="{{ route('profile.delete.confirm') }}" class="text-danger small">
                                    <i class="fas fa-user-times"></i> 退会手続き
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection