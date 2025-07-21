@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Edit User: {{ $user->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name</label>
                    <div class="input-group input-group-outline">
                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                    </div>
                    @error('name') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group input-group-outline">
                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                    </div>
                    @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">New Password</label>
                    <div class="input-group input-group-outline">
                        <input type="password" class="form-control" name="password">
                    </div>
                    <p class="text-sm text-muted">Leave blank to keep the current password.</p>
                    @error('password') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <div class="input-group input-group-outline">
                        <input type="password" class="form-control" name="password_confirmation">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <div class="input-group input-group-outline">
                    <select class="form-control" name="roles" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ $userRole == $role ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('roles') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
            </div>

            <button type="submit" class="btn btn-dark">Update User</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
