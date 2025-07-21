@extends('layouts.app')

@section('title', 'Create New Location')

@section('content')
<div class="card">
    <form action="{{ route('yard-locations.store') }}" method="POST">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Create New Location</h5>
            <div>
                <a href="{{ route('yard-locations.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-dark">Create Location</button>
            </div>
        </div>
        <div class="card-body">
            @include('yard-locations.partials._form')
        </div>
    </form>
</div>
@endsection
