@extends('layouts.app')

@section('title', 'Location Yard Master')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
                <h5 class="mb-3 mb-md-0">Location Yard Master</h5>
                <div class="d-flex align-items-center">
                    <form action="{{ route('yard-locations.index') }}" method="GET" class="me-2">
                        <div class="input-group input-group-outline {{ request('search') ? 'is-filled' : '' }}">
                            <label class="form-label">Search...</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                        </div>
                    </form>
                    @can('delete yard locations')
                        <button type="button" class="btn btn-danger mb-0 me-2" id="bulk-delete-btn" data-bs-toggle="modal"
                            data-bs-target="#bulkDeleteModal" disabled>
                            Delete Selected
                        </button>
                    @endcan
                    @can('create yard locations')
                        <a href="{{ route('yard-locations.create') }}" class="btn btn-dark mb-0">Add New Location</a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="p-4">
                @include('layouts.partials.alerts')
            </div>
            <form id="bulk-delete-form" action="{{ route('yard-locations.bulkDestroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 1%;">
                                    <div class="form-check d-flex justify-content-center"><input class="form-check-input"
                                            type="checkbox" id="select-all-checkbox"></div>
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Location Code</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Location Type</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Zone
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Area
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Bin
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($locations as $location)
                                <tr>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center"><input
                                                class="form-check-input location-checkbox" type="checkbox" name="ids[]"
                                                value="{{ $location->id }}"></div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-2">{{ $location->location_code }}</p>
                                    </td>
                                    {{-- ดึงชื่อ Location Type จากตาราง yard_categories ผ่าน Relationship ที่ชื่อ locationType --}}
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-2">
                                            {{ $location->locationType?->name ?? 'N/A' }}</p>
                                    </td>
                                    {{-- ดึงชื่อ Zone จากตาราง yard_categories ผ่าน Relationship ที่ชื่อ zone --}}
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-2">{{ $location->zone?->name ?? 'N/A' }}
                                        </p>
                                    </td>
                                    {{-- ดึงชื่อ Area จากตาราง yard_categories ผ่าน Relationship ที่ชื่อ area --}}
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-2">{{ $location->area?->name ?? 'N/A' }}
                                        </p>
                                    </td>
                                    {{-- ดึงชื่อ Bin จากตาราง yard_categories ผ่าน Relationship ที่ชื่อ bin --}}
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-2">{{ $location->bin?->name ?? 'N/A' }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if ($location->is_active)
                                            <span class="badge badge-sm bg-gradient-success">Active</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        @can('edit yard locations')
                                            <a href="{{ route('yard-locations.edit', $location->id) }}"
                                                class="btn btn-link text-secondary mb-0" title="Edit"><i
                                                    class="material-symbols-rounded">edit</i></a>
                                        @endcan
                                        @can('delete yard locations')
                                            <button type="button" class="btn btn-link text-danger mb-0" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal-{{ $location->id }}" title="Delete"><i
                                                    class="material-symbols-rounded">delete</i></button>
                                        @endcan
                                    </td>
                                </tr>
                                @include('yard-locations.partials.delete-modal', ['location' => $location])
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center p-3">No locations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex justify-content-between">
            {{ $locations->withQueryString()->links() }}
        </div>
    </div>
    @include('yard-locations.partials.bulk-delete-modal')
@endsection

@push('scripts')
    <script>
        // JavaScript for bulk delete checkbox
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            const locationCheckboxes = document.querySelectorAll('.location-checkbox');
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

            function toggleBulkDeleteBtn() {
                bulkDeleteBtn.disabled = !Array.from(locationCheckboxes).some(cb => cb.checked);
            }

            selectAllCheckbox.addEventListener('change', function() {
                locationCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
                toggleBulkDeleteBtn();
            });

            locationCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    selectAllCheckbox.checked = Array.from(locationCheckboxes).every(cb => cb
                        .checked);
                    toggleBulkDeleteBtn();
                });
            });
            toggleBulkDeleteBtn();
        });
    </script>
@endpush
