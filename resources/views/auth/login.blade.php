@extends('layouts.guest')

@section('title', 'Sign In')

@section('content')
<div class="page-header align-items-start min-vh-100" style="background-image: url('{{ asset('assets/img/Login.jpg') }}');">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container my-auto">
        <div class="row">
            <div class="col-lg-4 col-md-8 col-12 mx-auto">
                <div class="card z-index-0 fadeIn3 fadeInBottom">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                            {{-- 1. ย้ายโลโก้และชื่อระบบมาไว้ที่นี่ --}}
                            <div class="text-center">
                                <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" style="max-height: 90px; border-radius: 8px;" class="mb-2">
                                <h4 class="text-white font-weight-bolder">Logistics Management System</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form role="form" class="text-start" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Username</label>
                                <div class="input-group input-group-outline">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                                </div>
                                @error('name')<p class="text-danger text-xs p-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group input-group-outline">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                </div>
                                @error('password')<p class="text-danger text-xs p-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="form-check form-switch d-flex align-items-center mb-3">
                                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                                <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
