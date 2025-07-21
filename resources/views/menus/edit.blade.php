@extends('layouts.app')

@section('title', 'Edit Menu Item')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Edit Menu Item: {{ $menu->title }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('menus.update', $menu->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('menus.partials.form', ['menu' => $menu])
            <button type="submit" class="btn btn-dark">Update Menu Item</button>
            <a href="{{ route('menus.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
