@extends('layouts.app')

@section('title', 'Edit Permission')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Edit Permission: {{ $permission->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Permission Name</label>
                <div class="input-group input-group-outline">
                    <input type="text" class="form-control" id="name" name="name" value="{{ $permission->name }}" required>
                </div>
                 @error('name') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
            </div>
            <button type="submit" class="btn btn-dark">Update Permission</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection