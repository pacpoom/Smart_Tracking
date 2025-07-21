@extends('layouts.app')

@section('title', 'Create New Role')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create New Role</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Role Name</label>
                <div class="input-group input-group-outline">
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Permissions</label>
                
                {{-- 1. เพิ่มช่องค้นหา --}}
                <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Search Permissions...</label>
                    <input type="text" class="form-control" id="permission-search">
                </div>

                <div class="row" id="permissions-list">
                    @foreach($permissions as $permission)
                        <div class="col-md-3 permission-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                                <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-dark">Create Role</button>
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- 2. เพิ่ม JavaScript สำหรับการค้นหา --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('permission-search');
        const permissionsList = document.getElementById('permissions-list');
        const permissionItems = permissionsList.querySelectorAll('.permission-item');

        searchInput.addEventListener('keyup', function (e) {
            const searchTerm = e.target.value.toLowerCase();

            permissionItems.forEach(function (item) {
                const label = item.querySelector('label');
                const permissionName = label.textContent.toLowerCase();

                if (permissionName.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
