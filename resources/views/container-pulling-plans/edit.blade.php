@extends('layouts.app')

@section('title', 'Edit Pulling Plan')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <form action="{{ route('container-pulling-plans.update', $containerPullingPlan->id) }}" method="POST">
                @method('PUT')
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Edit Pulling Plan: {{ $containerPullingPlan->pulling_plan_no }}</h5>
                    <div>
                        <a href="{{ route('container-pulling-plans.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-dark">Update Plan</button>
                    </div>
                </div>
                <div class="card-body">
                    @include('container-pulling-plans.partials._form', ['containerPullingPlan' => $containerPullingPlan])
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
