@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Roles</h5>
        <div>
            {{-- 1. ปุ่มสำหรับลบข้อมูลที่เลือก --}}
            @can('delete roles')
                <button type="button" class="btn btn-danger mb-0" id="bulk-delete-btn" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal" disabled>
                    Delete Selected
                </button>
            @endcan
            @can('create roles')
                <a href="{{ route('roles.create') }}" class="btn btn-dark mb-0">Add New Role</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <form id="bulk-delete-form" action="{{ route('roles.bulkDestroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            {{-- 2. Checkbox สำหรับเลือกทั้งหมด --}}
                            <th class="text-center" style="width: 1%;">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                </div>
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Permissions</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr>
                            {{-- 3. Checkbox สำหรับแต่ละแถว --}}
                            <td class="text-center">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input role-checkbox" type="checkbox" name="ids[]" value="{{ $role->id }}">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-xs">{{ $role->name }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @foreach($role->permissions->take(5) as $permission)
                                    <span class="badge badge-sm bg-gradient-info">{{ $permission->name }}</span>
                                @endforeach
                                @if($role->permissions->count() > 5)
                                    <span class="badge badge-sm bg-gradient-secondary">...</span>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                @can('edit roles')
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-secondary mb-0">Edit</a>
                                @endcan
                                @can('delete roles')
                                    <button type="button" class="btn btn-sm btn-outline-danger mb-0" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $role->id }}">
                                        Delete
                                    </button>
                                @endcan
                            </td>
                        </tr>
                        {{-- Modal for Deletion Confirmation --}}
                        @can('delete roles')
                        <div class="modal fade" id="deleteModal-{{ $role->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $role->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel-{{ $role->id }}">Confirm Deletion</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete the role '<strong>{{ $role->name }}</strong>'? This action cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
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
        {{ $roles->links() }}
    </div>
</div>

{{-- 4. Modal สำหรับยืนยันการลบหลายรายการ --}}
<div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkDeleteModalLabel">Confirm Bulk Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the selected roles? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('bulk-delete-form').submit();">Confirm Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- 5. เพิ่ม JavaScript สำหรับจัดการ Checkbox --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const roleCheckboxes = document.querySelectorAll('.role-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

        function toggleBulkDeleteBtn() {
            const anyChecked = Array.from(roleCheckboxes).some(cb => cb.checked);
            bulkDeleteBtn.disabled = !anyChecked;
        }

        selectAllCheckbox.addEventListener('change', function () {
            roleCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleBulkDeleteBtn();
        });

        roleCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    const allChecked = Array.from(roleCheckboxes).every(cb => cb.checked);
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
