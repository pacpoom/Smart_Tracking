@extends('layouts.app')

@section('title', 'Container Master')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Container Master</h5>
            <div class="d-flex align-items-center">
                <form action="{{ route('containers.index') }}" method="GET" class="me-2">
                    <div class="input-group input-group-outline">
                        <label class="form-label">Search...</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                    </div>
                </form>
                @can('delete containers')
                    <button type="button" class="btn btn-danger mb-0 me-2" id="bulk-delete-btn" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal" disabled>
                        Delete Selected
                    </button>
                @endcan
                @can('create containers')
                    <a href="{{ route('containers.create') }}" class="btn btn-dark mb-0">Add New Container</a>
                @endcan
            </div>
        </div>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
        <div class="p-4">
            @include('layouts.partials.alerts')
        </div>
        <form id="bulk-delete-form" action="{{ route('containers.bulkDestroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 1%;"><div class="form-check d-flex justify-content-center"><input class="form-check-input" type="checkbox" id="select-all-checkbox"></div></th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Container No.</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Size</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Agent</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Container Owner</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($containers as $container)
                        <tr>
                            <td class="text-center"><div class="form-check d-flex justify-content-center"><input class="form-check-input container-checkbox" type="checkbox" name="ids[]" value="{{ $container->id }}"></div></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $container->container_no }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $container->size }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $container->agent }}</p></td>
                            @if ($container->container_owner == 0)
                            <td><p class="text-xs font-weight-bold mb-0 px-2">Rental</p></td>
                            @elseif ($container->container_owner == 1)
                            <td><p class="text-xs font-weight-bold mb-0 px-2">Owner</p></td>
                            @endif
                            <td class="align-middle text-center">
                                @can('edit containers')
                                    <a href="{{ route('containers.edit', $container->id) }}" class="btn btn-link text-secondary mb-0" title="Edit"><i class="material-symbols-rounded">edit</i></a>
                                @endcan
                                @can('delete containers')
                                    <button type="button" class="btn btn-link text-danger mb-0" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $container->id }}" title="Delete"><i class="material-symbols-rounded">delete</i></button>
                                @endcan
                            </td>
                        </tr>
                        @include('containers.partials.delete-modal', ['container' => $container])
                        @empty
                        <tr><td colspan="5" class="text-center p-3">No containers found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <div class="card-footer d-flex justify-content-between">
        {{ $containers->withQueryString()->links() }}
    </div>
</div>
@include('containers.partials.bulk-delete-modal')
@endsection

@push('scripts')
<script>
    // JavaScript for bulk delete checkbox
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const containerCheckboxes = document.querySelectorAll('.container-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

        function toggleBulkDeleteBtn() {
            bulkDeleteBtn.disabled = !Array.from(containerCheckboxes).some(cb => cb.checked);
        }

        selectAllCheckbox.addEventListener('change', function () {
            containerCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            toggleBulkDeleteBtn();
        });

        containerCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                selectAllCheckbox.checked = Array.from(containerCheckboxes).every(cb => cb.checked);
                toggleBulkDeleteBtn();
            });
        });
        toggleBulkDeleteBtn();
    });
</script>
@endpush
