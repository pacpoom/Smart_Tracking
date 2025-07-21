@extends('layouts.app')

@section('title', 'Create New User')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create New User</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name</label>
                    <div class="input-group input-group-outline">
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                    @error('name') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group input-group-outline">
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    </div>
                    @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group input-group-outline">
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    @error('password') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-group input-group-outline">
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <div class="input-group input-group-outline">
                    <select class="form-control" name="roles" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}">{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
                @error('roles') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
            </div>

            <button type="submit" class="btn btn-dark">Create User</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
