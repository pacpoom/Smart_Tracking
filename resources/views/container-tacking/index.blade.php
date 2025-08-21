@extends('layouts.app')

@section('title', 'Container Tacking List')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Container Tacking List</h5>
            <div class="d-flex align-items-center">
                <form action="{{ route('container-tacking.index') }}" method="GET" class="me-2">
                    <div class="input-group input-group-outline">
                        <label class="form-label">Search by</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                    </div>
                </form>
                @can('delete container tackings')
                    <button type="button" class="btn btn-danger mb-0 me-2" id="bulk-delete-btn" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal" disabled>
                        Delete Selected
                    </button>
                @endcan
            </div>
        </div>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
        <div class="p-4">
            @include('layouts.partials.alerts')
        </div>
        <form id="bulk-delete-form" action="{{ route('container-tacking.bulkDestroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 1%;"><div class="form-check d-flex justify-content-center"><input class="form-check-input" type="checkbox" id="select-all-checkbox"></div></th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Container No.</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Shipment / B/L</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Job Type</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">User</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tackings as $tacking)
                        <tr>
                            <td class="text-center"><div class="form-check d-flex justify-content-center"><input class="form-check-input tacking-checkbox" type="checkbox" name="ids[]" value="{{ $tacking->id }}"></div></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $tacking->containerOrderPlan?->container?->container_no ?? 'N/A' }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $tacking->containerOrderPlan?->house_bl ?? $tacking->shipment }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $tacking->job_type }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $tacking->user->name }}</p></td>
                            <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $tacking->created_at->format('d/m/Y H:i') }}</span></td>
                            <td class="align-middle text-center">
                                <a href="{{ route('container-tacking.show', $tacking->id) }}" class="btn btn-link text-secondary mb-0" title="View Details">
                                    <i class="material-symbols-rounded">visibility</i>
                                </a>
                                {{-- Added Edit Button --}}
                                @can('edit container tackings')
                                <a href="{{ route('container-tacking.edit', $tacking->id) }}" class="btn btn-link text-dark px-3 mb-0" title="Edit">
                                    <i class="material-symbols-rounded">edit</i>
                                </a>
                                @endcan
                                @can('delete container tackings')
                                    <button type="button" class="btn btn-link text-danger mb-0" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $tacking->id }}" title="Delete">
                                        <i class="material-symbols-rounded">delete</i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                        @include('container-tacking.partials.delete-modal', ['tacking' => $tacking])
                        @empty
                        <tr><td colspan="7" class="text-center p-3">No tacking records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <div class="card-footer d-flex justify-content-between">
        {{ $tackings->withQueryString()->links() }}
    </div>
</div>
@include('container-tacking.partials.bulk-delete-modal')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const tackingCheckboxes = document.querySelectorAll('.tacking-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

        function toggleBulkDeleteBtn() {
            bulkDeleteBtn.disabled = !Array.from(tackingCheckboxes).some(cb => cb.checked);
        }

        selectAllCheckbox.addEventListener('change', function () {
            tackingCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            toggleBulkDeleteBtn();
        });

        tackingCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                selectAllCheckbox.checked = Array.from(tackingCheckboxes).every(cb => cb.checked);
                toggleBulkDeleteBtn();
            });
        });
        toggleBulkDeleteBtn();
    });
</script>
@endpush
