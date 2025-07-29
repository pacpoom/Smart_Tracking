@extends('layouts.app')

@section('title', 'Create New Pulling Plan')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <form action="{{ route('container-pulling-plans.store') }}" method="POST">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Create New Pulling Plan</h5>
                    <div>
                        <a href="{{ route('container-pulling-plans.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-dark">Create Plan</button>
                    </div>
                </div>
                <div class="card-body">
                    @include('container-pulling-plans.partials._form')
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
