@extends('layouts.app')

@section('title', 'Vendor Master')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Vendor Master</h5>
            <div class="d-flex align-items-center">
                {{-- 1. ฟอร์มค้นหา --}}
                <form action="{{ route('vendors.index') }}" method="GET" class="me-2">
                    <div class="input-group input-group-outline">
                        <label class="form-label">Search...</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                    </div>
                </form>
                @can('delete vendors')
                    <button type="button" class="btn btn-danger mb-0 me-2" id="bulk-delete-btn" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal" disabled>
                        Delete Selected
                    </button>
                @endcan
                @can('create vendors')
                    <a href="{{ route('vendors.create') }}" class="btn btn-dark mb-0">Add New Vendor</a>
                @endcan
            </div>
        </div>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
        
        {{-- แสดงข้อความ Success/Error --}}
        <div class="p-4">
            @include('layouts.partials.alerts')
        </div>

        <form id="bulk-delete-form" action="{{ route('vendors.bulkDestroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive p-0">
                {{-- 2. แก้ไข: ลบคลาส table-bordered ออก --}}
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 1%;">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                </div>
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Vendor Code</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Register Date</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Expire Date</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vendors as $vendor)
                        <tr>
                            <td class="text-center">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input vendor-checkbox" type="checkbox" name="ids[]" value="{{ $vendor->id }}">
                                </div>
                            </td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $vendor->vendor_code }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $vendor->name }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $vendor->register_date?->format('d/m/Y') }}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $vendor->expire_date?->format('d/m/Y') }}</p></td>
                            <td class="align-middle text-center text-sm">
                                @if($vendor->is_active)
                                    <span class="badge badge-sm bg-gradient-success">Active</span>
                                @else
                                    <span class="badge badge-sm bg-gradient-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                @if($vendor->attachment_path)
                                    <a href="{{ route('vendors.download', $vendor->id) }}" class="btn btn-link text-secondary mb-0" title="Download Attachment">
                                        <i class="material-symbols-rounded">download</i>
                                    </a>
                                @endif
                                @can('edit vendors')
                                    <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-link text-secondary mb-0" title="Edit">
                                        <i class="material-symbols-rounded">edit</i>
                                    </a>
                                @endcan
                                @can('delete vendors')
                                    <button type="button" class="btn btn-link text-danger mb-0" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $vendor->id }}" title="Delete">
                                        <i class="material-symbols-rounded">delete</i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                        @include('vendors.partials.delete-modal', ['vendor' => $vendor])
                        @empty
                        <tr>
                            <td colspan="7" class="text-center p-3">No vendors found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <div class="card-footer d-flex justify-content-between">
        {{ $vendors->withQueryString()->links() }}
    </div>
</div>
@include('vendors.partials.bulk-delete-modal')
@endsection

@push('scripts')
<script>
    // JavaScript for bulk delete checkbox
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const vendorCheckboxes = document.querySelectorAll('.vendor-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

        function toggleBulkDeleteBtn() {
            const anyChecked = Array.from(vendorCheckboxes).some(cb => cb.checked);
            bulkDeleteBtn.disabled = !anyChecked;
        }

        selectAllCheckbox.addEventListener('change', function () {
            vendorCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            toggleBulkDeleteBtn();
        });

        vendorCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                selectAllCheckbox.checked = Array.from(vendorCheckboxes).every(cb => cb.checked);
                toggleBulkDeleteBtn();
            });
        });
        toggleBulkDeleteBtn();
    });
</script>
@endpush
