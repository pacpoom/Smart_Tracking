@extends('layouts.app')

@section('title', 'Production Plan')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Production Plan List</h5>
                <div>
                    <a href="{{ route('production-plans.export', request()->query()) }}" class="btn btn-success mb-0">Export
                        to CSV</a>
                    <a href="{{ route('production-plans.create') }}" class="btn btn-dark mb-0">Create New Plan</a>
                </div>
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="p-4">
                @include('layouts.partials.alerts')
                <form action="{{ route('production-plans.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group input-group-outline {{ request('search') ? 'is-filled' : '' }}">
                                <label class="form-label">Search by Plan No or VC Code...</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Plan No</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">VC Code
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Model</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-end">
                                Production Order</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Production
                                Date</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productionPlans as $plan)
                            <tr>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-3">{{ $plan->plan_no }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $plan->vcMaster->vc_code }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $plan->vcMaster->model }}</p>
                                </td>
                                <td class="text-end">
                                    <p class="text-xs font-weight-bold mb-0">{{ number_format($plan->production_order) }}
                                    </p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $plan->production_date->format('Y-m-d') }}
                                    </p>
                                </td>
                                <td><span class="badge badge-sm bg-gradient-info">{{ ucfirst($plan->status) }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('production-plans.show', $plan->id) }}"
                                        class="btn btn-secondary btn-sm mb-0">View Details</a>
                                    <a href="{{ route('production-plans.edit', $plan->id) }}"
                                        class="btn btn-info btn-sm mb-0">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm mb-0" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal-{{ $plan->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @include('production-plans.partials.delete-modal', ['productionPlan' => $plan])
                        @empty
                            <tr>
                                <td colspan="7" class="text-center p-3">No production plans found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $productionPlans->withQueryString()->links() }}
        </div>
    </div>
@endsection
