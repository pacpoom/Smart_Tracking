@extends('layouts.app')

@section('title', 'Add New PFEP')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Add New PFEP</h5>
        </div>
        <div class="card-body">
            @include('layouts.partials.alerts')
            <form action="{{ route('pfeps.store') }}" method="POST">
                @include('pfeps.partials._form')
                <div class="mt-4">
                    <button type="submit" class="btn btn-dark">Create PFEP</button>
                    <a href="{{ route('pfeps.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
