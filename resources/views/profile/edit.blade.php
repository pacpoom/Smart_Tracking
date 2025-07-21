@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="row">
        {{-- คอลัมน์ซ้าย: ข้อมูลส่วนตัวและรหัสผ่าน --}}
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">Update Profile Information</div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card">
                <div class="card-header">Update Password</div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        {{-- คอลัมน์ขวา: รูปโปรไฟล์ --}}
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">Profile Photo</div>
                <div class="card-body text-center">
                    {{-- แสดงรูปโปรไฟล์ปัจจุบัน --}}
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="avatar avatar-xxl rounded-circle shadow-sm mb-3">
                    
                    @if (session('status') === 'profile-photo-updated')
                        <p class="text-sm text-success">Photo updated successfully.</p>
                    @endif

                    <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group input-group-outline my-3">
                            <input type="file" name="photo" class="form-control" required>
                        </div>
                        @error('photo') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        <button type="submit" class="btn btn-dark mt-2">Upload Photo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
