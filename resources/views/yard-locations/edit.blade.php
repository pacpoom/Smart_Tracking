@extends('layouts.app')

@section('title', 'Edit Location')

@section('content')
<div class="card">
    <form action="{{ route('yard-locations.update', $yardLocation->id) }}" method="POST">
        @method('PUT')
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Edit Location: {{ $yardLocation->location_code }}</h5>
            <div>
                <a href="{{ route('yard-locations.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-dark">Update Location</button>
            </div>
        </div>
        <div class="card-body">
            @include('yard-locations.partials._form', ['yardLocation' => $yardLocation])
        </div>
    </form>
</div>
@endsection
