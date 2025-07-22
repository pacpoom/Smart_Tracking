@extends('layouts.app')

@section('title', 'Container Order Plan')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Container Order Plan</h5>
            <div class="d-flex align-items-center">
                @can('delete container plans')
                    <button type="button" class="btn btn-danger mb-0 me-2" id="bulk-delete-btn" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal" disabled>
                        Delete Selected
                    </button>
                @endcan
                @can('create container plans')
                    <button type="button" class="btn btn-info mb-0 me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                        Import
                    </button>
                    <a href="{{ route('container-order-plans.template') }}" class="btn btn-success mb-0 me-2">Download Template</a>
                    <a href="{{ route('container-order-plans.create') }}" class="btn btn-dark mb-0">Add New Plan</a>
                @endcan
            </div>
        </div>
    </div>
    <div class="card-body">
        {{-- Advanced Search Form --}}
        <form action="{{ route('container-order-plans.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Search by Container No. / B/L</label>
                    <div class="input-group input-group-outline">
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">ETA Date From</label>
                    <div class="input-group input-group-outline">
                        <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">ETA Date To</label>
                    <div class="input-group input-group-outline">
                        <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex">
                    <button type="submit" class="btn btn-dark me-2">Search</button>
                    <a href="{{ route('container-order-plans.export', request()->query()) }}" class="btn btn-success">Export</a>
                </div>
            </div>
        </form>

        <div class="p-4">
            @include('layouts.partials.alerts')
        </div>

        <form id="bulk-delete-form" action="{{ route('container-order-plans.bulkDestroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 1%;"><div class="form-check d-flex justify-content-center"><input class="form-check-input" type="checkbox" id="select-all-checkbox"></div></th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Plan No.</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Container No.</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">House B/L</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ETA Date</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Check-in Date</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Departure Date</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- แก้ไข: เปลี่ยน $requests เป็น $plans --}}
                        @forelse ($plans as $plan)
                        <tr>
                            <td class="text-center"><div class="form-check d-flex justify-content-center"><input class="form-check-input plan-checkbox" type="checkbox" name="ids[]" value="{{ $plan->id }}"></div></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $plan->plan_no }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $plan->container->container_no }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $plan->house_bl }}</p></td>
                            <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $plan->eta_date?->format('d/m/Y') }}</span></td>
                            <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $plan->checkin_date?->format('d/m/Y') }}</span></td>
                            <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $plan->departure_date?->format('d/m/Y') }}</span></td>
                            <td class="align-middle text-center text-sm">
                                @if($plan->status == 1)
                                    <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                @elseif($plan->status == 2)
                                    <span class="badge badge-sm bg-gradient-success">Received</span>
                                @elseif($plan->status == 3)
                                    <span class="badge badge-sm bg-gradient-info">Shipped Out</span>
                                @else
                                    <span class="badge badge-sm bg-gradient-secondary">Unknown</span>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                @can('edit container plans')
                                    <a href="{{ route('container-order-plans.edit', $plan->id) }}" class="btn btn-link text-secondary mb-0" title="Edit"><i class="material-symbols-rounded">edit</i></a>
                                @endcan
                                @can('delete container plans')
                                    <button type="button" class="btn btn-link text-danger mb-0" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $plan->id }}" title="Delete"><i class="material-symbols-rounded">delete</i></button>
                                @endcan
                            </td>
                        </tr>
                        @include('container-order-plans.partials.delete-modal', ['plan' => $plan])
                        @empty
                        <tr><td colspan="8" class="text-center p-3">No container order plans found for the selected date range.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <div class="card-footer d-flex justify-content-between">
        {{-- แก้ไข: เปลี่ยน $requests เป็น $plans --}}
        {{ $plans->withQueryString()->links() }}
    </div>
</div>
@include('container-order-plans.partials.bulk-delete-modal')

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('container-order-plans.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Container Plans</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Please upload an Excel file with the correct format. You can download the template if you don't have one.</p>
                    <div class="input-group input-group-outline">
                        <input class="form-control" type="file" name="import_file" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Upload and Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // JavaScript for bulk delete checkbox
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
