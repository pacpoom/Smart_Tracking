@extends('layouts.app')

@section('title', 'Permission Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Permissions</h5>
        <div>
            {{-- 1. ปุ่มสำหรับลบข้อมูลที่เลือก --}}
            @can('delete permissions')
                <button type="button" class="btn btn-danger mb-0" id="bulk-delete-btn" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal" disabled>
                    Delete Selected
                </button>
            @endcan
            @can('create permissions')
                <a href="{{ route('permissions.create') }}" class="btn btn-dark mb-0">Add New Permission</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <form id="bulk-delete-form" action="{{ route('permissions.bulkDestroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 1%;">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                </div>
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Permission Name</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                        <tr>
                            <td class="text-center">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input permission-checkbox" type="checkbox" name="ids[]" value="{{ $permission->id }}">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{ $permission->name }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle text-center">
                                @can('edit permissions')
                                    <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-outline-secondary mb-0">Edit</a>
                                @endcan
                                @can('delete permissions')
                                    <button type="button" class="btn btn-sm btn-outline-danger mb-0" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $permission->id }}">
                                        Delete
                                    </button>
                                @endcan
                            </td>
                        </tr>
                        {{-- Modal for single deletion --}}
                        @can('delete permissions')
                        <div class="modal fade" id="deleteModal-{{ $permission->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Deletion</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete the permission '<strong>{{ $permission->name }}</strong>'?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <div class="card-footer d-flex justify-content-between">
        {{ $permissions->links() }}
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
                Are you sure you want to delete the selected permissions? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('bulk-delete-form').submit();">Confirm Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

        function toggleBulkDeleteBtn() {
            const anyChecked = Array.from(permissionCheckboxes).some(cb => cb.checked);
            bulkDeleteBtn.disabled = !anyChecked;
        }

        selectAllCheckbox.addEventListener('change', function () {
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleBulkDeleteBtn();
        });

        permissionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
                toggleBulkDeleteBtn();
            });
        });

        // Initial check
        toggleBulkDeleteBtn();
    });
</script>
@endpush
