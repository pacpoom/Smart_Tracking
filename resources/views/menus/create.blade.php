@extends('layouts.app')

@section('title', 'Create Menu Item')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create New Menu Item</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('menus.store') }}" method="POST">
            @csrf
            @include('menus.partials.form')
            <button type="submit" class="btn btn-dark">Create Menu Item</button>
            <a href="{{ route('menus.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
