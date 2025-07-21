@extends('layouts.app')

@section('title', 'Create New Permission')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create New Permission</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Permission Name</label>
                <div class="input-group input-group-outline">
                    <input type="text" class="form-control" id="name" name="name" required placeholder="e.g., edit articles">
                </div>
                 @error('name') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
            </div>
            <button type="submit" class="btn btn-dark">Create Permission</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection