@extends('layouts.app')

@section('title', 'Stock Management')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Stock Management</h5>
            <div class="d-flex align-items-center">
                <form action="{{ route('stocks.index') }}" method="GET" class="me-2">
                    <div class="input-group input-group-outline">
                        <label class="form-label">Search by Part Number/Name...</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                    </div>
                </form>
                @can('create stock')
                    <button type="button" class="btn btn-dark mb-0" data-bs-toggle="modal" data-bs-target="#createStockModal">
                        Add New Part Stock
                    </button>
                @endcan
            </div>
        </div>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
        <div class="p-4">
            @include('layouts.partials.alerts')
        </div>
        <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Part Number</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Part Name</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Current Quantity</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($parts as $part)
                    <tr>
                        <td>
                            <p class="text-xs font-weight-bold mb-0 px-2">{{ $part->part_number }}</p>
                        </td>
                        <td>
                            <p class="text-xs font-weight-bold mb-0 px-2">{{ $part->part_name_eng ?: $part->part_name_thai }}</p>
                        </td>
                        <td class="align-middle text-center">
                            <span class="text-secondary text-xs font-weight-bold">{{ $part->stock?->qty ?? 0 }}</span>
                        </td>
                        <td class="align-middle text-center">
                            @can('adjust stock')
                                {{-- แก้ไข: ลบ @if และเปลี่ยน data-bs-target ให้ใช้ $part->id --}}
                                <button type="button" class="btn btn-link text-secondary mb-0" data-bs-toggle="modal" data-bs-target="#adjustModal-{{ $part->id }}" title="Adjust Stock">
                                    <i class="material-symbols-rounded">tune</i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                    {{-- แก้ไข: ส่ง $part ไปยัง modal --}}
                    @include('stocks.partials.adjust-modal', ['part' => $part])
                    @empty
                    <tr><td colspan="4" class="text-center p-3">No parts found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        {{ $parts->withQueryString()->links() }}
    </div>
</div>

{{-- Modal for Creating New Part and Stock --}}
@can('create stock')
<div class="modal fade" id="createStockModal" tabindex="-1" aria-labelledby="createStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('stocks.storePartAndStock') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createStockModalLabel">Add New Part and Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Part Number</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="part_number" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Initial Quantity</label>
                            <div class="input-group input-group-outline">
                                <input type="number" class="form-control" name="qty" value="0" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Part Name (TH)</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="part_name_thai">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Part Name (EN)</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="part_name_eng">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unit</label>
                        <div class="input-group input-group-outline">
                            <input type="text" class="form-control" name="unit" placeholder="e.g., PCS, KG, SET">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@endsection
