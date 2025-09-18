@extends('layouts.app')

@section('title', 'Edit PFEP')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Edit PFEP for: {{ $pfep->material->material_number }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pfeps.update', $pfep->id) }}" method="POST">
                @method('PUT')
                @include('pfeps.partials._form', ['pfep' => $pfep])
                <div class="mt-4">
                    <button type="submit" class="btn btn-dark">Update PFEP</button>
                    <a href="{{ route('pfeps.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
