@extends('layouts.app')

@section('title', 'Edit Vendor')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Edit Vendor: {{ $vendor->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('vendors.update', $vendor->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('vendors.partials._form', ['vendor' => $vendor])
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Update Vendor</button>
                <a href="{{ route('vendors.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
