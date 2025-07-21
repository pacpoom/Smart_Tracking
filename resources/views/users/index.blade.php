@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Users</h5>
        <div>
            @can('manage users')
                {{-- ปุ่มสำหรับลบข้อมูลที่เลือก --}}
                <button type="button" class="btn btn-danger mb-0" id="bulk-delete-btn" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal" disabled>
                    Delete Selected
                </button>
                <a href="{{ route('users.create') }}" class="btn btn-dark mb-0">Add New User</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <form id="bulk-delete-form" action="{{ route('users.bulkDestroy') }}" method="POST">
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
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created At</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td class="text-center">
                                <div class="form-check d-flex justify-content-center">
                                    {{-- ป้องกันไม่ให้เลือก user ตัวเอง --}}
                                    @if($user->id != auth()->id())
                                        <input class="form-check-input user-checkbox" type="checkbox" name="ids[]" value="{{ $user->id }}">
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                        <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge badge-sm bg-gradient-success">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="align-middle text-center text-sm">
                                <span class="text-secondary text-xs font-weight-normal">{{ $user->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="align-middle text-center">
                                @can('manage users')
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary mb-0">Edit</a>
                                    {{-- ป้องกันไม่ให้แสดงปุ่มลบ user ตัวเอง --}}
                                    @if($user->id != auth()->id())
                                    <button type="button" class="btn btn-sm btn-outline-danger mb-0" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $user->id }}">
                                        Delete
                                    </button>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                        {{-- Modal for single deletion --}}
                        @can('manage users')
                        <div class="modal fade" id="deleteModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Deletion</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete user '<strong>{{ $user->name }}</strong>'?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
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
        {{ $users->links() }}
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
                Are you sure you want to delete the selected users? This action cannot be undone.
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
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

        function toggleBulkDeleteBtn() {
            const anyChecked = Array.from(userCheckboxes).some(cb => cb.checked);
            bulkDeleteBtn.disabled = !anyChecked;
        }

        selectAllCheckbox.addEventListener('change', function () {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleBulkDeleteBtn();
        });

        userCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    const allChecked = Array.from(userCheckboxes).every(cb => cb.checked);
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
