@extends('layouts.app')

@section('title', 'Add New Material')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Add New Material</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('materials.store') }}" method="POST">
                @include('materials.partials._form')
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('materials.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
