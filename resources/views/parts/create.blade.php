@extends('layouts.app')

@section('title', 'Create New Part')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create New Part</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('parts.store') }}" method="POST">
            @include('parts.partials._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Create Part</button>
                <a href="{{ route('parts.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
