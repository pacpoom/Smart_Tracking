@extends('layouts.app')

@section('title', 'Edit Material')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Material</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('materials.update', $material->id) }}" method="POST">
                @method('PUT')
                @include('materials.partials._form')
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('materials.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
