@extends('layouts.app')

@section('title', 'Create New Vendor')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create New Vendor</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('vendors.store') }}" method="POST" enctype="multipart/form-data">
            @include('vendors.partials._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Create Vendor</button>
                <a href="{{ route('vendors.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
