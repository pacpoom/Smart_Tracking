@extends('layouts.app')

@section('title', 'Container Pulling Plan')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Container Pulling Plan</h5>
            <div class="d-flex align-items-center">
                <form action="{{ route('container-pulling-plans.index') }}" method="GET" class="me-2">
                    <div class="input-group input-group-outline">
                        <label class="form-label">Search...</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                    </div>
                </form>
                @can('delete pulling plans')
                    <button type="button" class="btn btn-danger mb-0 me-2" id="bulk-delete-btn" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal" disabled>
                        Delete Selected
                    </button>
                @endcan
                @can('create pulling plans')
                    <a href="{{ route('container-pulling-plans.create') }}" class="btn btn-dark mb-0">Add New Plan</a>
                @endcan
            </div>
        </div>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
        <div class="p-4">
            @include('layouts.partials.alerts')
        </div>
        <form id="bulk-delete-form" action="{{ route('container-pulling-plans.bulkDestroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 1%;"><div class="form-check d-flex justify-content-center"><input class="form-check-input" type="checkbox" id="select-all-checkbox"></div></th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pulling Plan No.</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Plan Type</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Container No.</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Destination</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pulling Date</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pulling Order</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pullingPlans as $plan)
                        <tr>
                            <td class="text-center"><div class="form-check d-flex justify-content-center"><input class="form-check-input plan-checkbox" type="checkbox" name="ids[]" value="{{ $plan->id }}"></div></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $plan->pulling_plan_no }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $plan->plan_type }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $plan->containerOrderPlan->container->container_no }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $plan->destination }}</p></td>
                            <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $plan->pulling_date?->format('d/m/Y') }}</span></td>
                            <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $plan->pulling_order }}</span></td>
                            <td class="align-middle text-center text-sm">
                                @if($plan->status == 1)
                                    <span class="badge badge-sm bg-gradient-secondary">Planned</span>
                                @elseif($plan->status == 2)
                                    <span class="badge badge-sm bg-gradient-info">In Progress</span>
                                @elseif($plan->status == 3)
                                    <span class="badge badge-sm bg-gradient-success">Completed</span>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                @can('edit pulling plans')
                                    <a href="{{ route('container-pulling-plans.edit', $plan->id) }}" class="btn btn-link text-secondary mb-0" title="Edit"><i class="material-symbols-rounded">edit</i></a>
                                @endcan
                                @can('delete pulling plans')
                                    <button type="button" class="btn btn-link text-danger mb-0" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $plan->id }}" title="Delete"><i class="material-symbols-rounded">delete</i></button>
                                @endcan
                            </td>
                        </tr>
                        @include('container-pulling-plans.partials.delete-modal', ['plan' => $plan])
                        @empty
                        <tr><td colspan="8" class="text-center p-3">No pulling plans found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <div class="card-footer d-flex justify-content-between">
        {{ $pullingPlans->withQueryString()->links() }}
    </div>
</div>
@include('container-pulling-plans.partials.bulk-delete-modal')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const planCheckboxes = document.querySelectorAll('.plan-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

        function toggleBulkDeleteBtn() {
            bulkDeleteBtn.disabled = !Array.from(planCheckboxes).some(cb => cb.checked);
        }

        selectAllCheckbox.addEventListener('change', function () {
            planCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            toggleBulkDeleteBtn();
        });

        planCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                selectAllCheckbox.checked = Array.from(planCheckboxes).every(cb => cb.checked);
                toggleBulkDeleteBtn();
            });
        });
        toggleBulkDeleteBtn();
    });
</script>
@endpush
