@extends('layouts.app')

@section('title', 'Part Master')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Part Master</h5>
            <div class="d-flex align-items-center">
                <form action="{{ route('parts.index') }}" method="GET" class="me-2">
                    <div class="input-group input-group-outline">
                        <label class="form-label">Search...</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                    </div>
                </form>
                @can('delete parts')
                    <button type="button" class="btn btn-danger mb-0 me-2" id="bulk-delete-btn" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal" disabled>
                        Delete Selected
                    </button>
                @endcan
                @can('create parts')
                    <a href="{{ route('parts.create') }}" class="btn btn-dark mb-0">Add New Part</a>
                @endcan
            </div>
        </div>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
        <div class="p-4">
            @include('layouts.partials.alerts')
        </div>
        <form id="bulk-delete-form" action="{{ route('parts.bulkDestroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 1%;"><div class="form-check d-flex justify-content-center"><input class="form-check-input" type="checkbox" id="select-all-checkbox"></div></th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Part Number</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Part Name (TH)</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Model No.</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($parts as $part)
                        <tr>
                            <td class="text-center"><div class="form-check d-flex justify-content-center"><input class="form-check-input part-checkbox" type="checkbox" name="ids[]" value="{{ $part->id }}"></div></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $part->part_number }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $part->part_name_thai }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $part->model_no }}</p></td>
                            <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $part->unit }}</span></td>
                            <td class="align-middle text-center">
                                @can('edit parts')
                                    <a href="{{ route('parts.edit', $part->id) }}" class="btn btn-link text-secondary mb-0" title="Edit"><i class="material-symbols-rounded">edit</i></a>
                                @endcan
                                @can('delete parts')
                                    <button type="button" class="btn btn-link text-danger mb-0" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $part->id }}" title="Delete"><i class="material-symbols-rounded">delete</i></button>
                                @endcan
                            </td>
                        </tr>
                        @include('parts.partials.delete-modal', ['part' => $part])
                        @empty
                        <tr><td colspan="6" class="text-center p-3">No parts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <div class="card-footer d-flex justify-content-between">
        {{ $parts->withQueryString()->links() }}
    </div>
</div>
@include('parts.partials.bulk-delete-modal')
@endsection

@push('scripts')
<script>
    // JavaScript for bulk delete checkbox
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const partCheckboxes = document.querySelectorAll('.part-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

        function toggleBulkDeleteBtn() {
            bulkDeleteBtn.disabled = !Array.from(partCheckboxes).some(cb => cb.checked);
        }

        selectAllCheckbox.addEventListener('change', function () {
            partCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            toggleBulkDeleteBtn();
        });

        partCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                selectAllCheckbox.checked = Array.from(partCheckboxes).every(cb => cb.checked);
                toggleBulkDeleteBtn();
            });
        });
        toggleBulkDeleteBtn();
    });
</script>
@endpush
