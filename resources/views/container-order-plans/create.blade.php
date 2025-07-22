@extends('layouts.app')

@section('title', 'Create New Container Plan')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <form action="{{ route('container-order-plans.store') }}" method="POST" enctype="multipart/form-data">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Create New Container Plan</h5>
                    <div>
                        <a href="{{ route('container-order-plans.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-dark">Create Plan</button>
                    </div>
                </div>
                <div class="card-body">
                    @include('container-order-plans.partials._form')
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
