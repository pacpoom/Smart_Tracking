@extends('layouts.app')

@section('title', 'Menu Management')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
                <h5 class="mb-3 mb-md-0">Menu Structure</h5>
                <div class="d-flex align-items-center">
                    <form action="{{ route('menus.index') }}" method="GET" class="me-2">
                        <div class="input-group input-group-outline {{ request('search') ? 'is-filled' : '' }}">
                            <label class="form-label">Search by Title...</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                        </div>
                    </form>
                    @can('manage menus')
                        <button type="button" class="btn btn-danger mb-0 me-2" id="bulk-delete-btn" data-bs-toggle="modal"
                            data-bs-target="#bulkDeleteModal" disabled>
                            Delete Selected
                        </button>
                    @endcan
                    <a href="{{ route('menus.create') }}" class="btn btn-dark mb-0">Add New Menu Item</a>
                </div>
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="p-4">
                @include('layouts.partials.alerts')
            </div>
            <form id="bulk-delete-form" action="{{ route('menus.bulkDestroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 1%;">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                    </div>
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Title
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Route
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Permission</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Order</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($menus as $menu)
                                @include('menus.partials.menu-item', ['menu' => $menu, 'level' => 0])
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center p-3">No menu items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex justify-content-between">
            {{ $menus->withQueryString()->links() }}
        </div>
    </div>

    {{-- Modal for bulk deletion --}}
    <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkDeleteModalLabel">Confirm Bulk Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the selected menu items and all their children? This action cannot be
                    undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger"
                        onclick="document.getElementById('bulk-delete-form').submit();">Confirm Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            const menuCheckboxes = document.querySelectorAll('.menu-checkbox');
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

            function toggleBulkDeleteBtn() {
                const anyChecked = Array.from(menuCheckboxes).some(cb => cb.checked);
                bulkDeleteBtn.disabled = !anyChecked;
            }

            selectAllCheckbox.addEventListener('change', function() {
                menuCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                toggleBulkDeleteBtn();
            });

            menuCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (!this.checked) {
                        selectAllCheckbox.checked = false;
                    } else {
                        const allChecked = Array.from(menuCheckboxes).every(cb => cb.checked);
                        selectAllCheckbox.checked = allChecked;
                    }
                    toggleBulkDeleteBtn();
                });
            });

            toggleBulkDeleteBtn();
        });
    </script>
@endpush
