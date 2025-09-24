@extends('layouts.app')

@section('title', 'Edit Production Plan')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Production Plan: {{ $productionPlan->plan_no }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('production-plans.update', $productionPlan->id) }}" method="POST" id="production-plan-form">
            @method('PUT')
            @include('production-plans.partials._form', ['productionPlan' => $productionPlan])

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Plan</button>
                <a href="{{ route('production-plans.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<hr class="horizontal dark mt-4 mb-3">

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Material Details Preview</h5>
    </div>
    <div class="card-body">
        <div id="materials-container" class="table-responsive">
            <p class="text-muted text-center" id="placeholder-text">Select a VC Code and enter production order to see required materials.</p>
            <table class="table align-items-center mb-0 d-none" id="materials-table">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material Number</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Material Name</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-end">BOM Qty</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-end">Required Qty</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-end">Stock Qty</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="materials-tbody">
                    <!-- Dynamic content will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection