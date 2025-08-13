@extends('layouts.app')

@section('title', 'Edit Container Plan')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <form action="{{ route('container-order-plans.update', $containerOrderPlan->id) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Edit Plan for: {{ $containerOrderPlan->container->container_no }}</h5>
                    <div>
                        <a href="{{ route('container-order-plans.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-dark">Update Plan</button>
                    </div>
                </div>
                <div class="card-body">
                    @include('container-order-plans.Partials._form', ['containerOrderPlan' => $containerOrderPlan])
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
