@extends('layouts.app')

@section('title', 'Edit Part')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Edit Part: {{ $part->part_number }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('parts.update', $part->id) }}" method="POST">
            @method('PUT')
            @include('parts.partials._form', ['part' => $part])
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Update Part</button>
                <a href="{{ route('parts.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
