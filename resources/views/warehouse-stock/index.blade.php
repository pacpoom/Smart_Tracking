@extends('layouts.app')

@section('title', 'Warehouse Stock')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Warehouse Stock</h5>
                <a href="{{ route('warehouse-stock.export', request()->query()) }}" class="btn btn-success mb-0">
                    <i class="fas fa-file-excel me-1"></i> Export to CSV
                </a>
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="p-4">
                @include('layouts.partials.alerts')

                <!-- Search and Filters -->
                <form action="{{ route('warehouse-stock.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group input-group-outline">
                                <label class="form-label">Search...</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary mb-0">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Material Number</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Material Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Model</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Part Type</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ULOC</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pull Type</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Line Side</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $stock)
                            <tr>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->material?->material_number ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->material?->material_name ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->material?->primaryPfep?->model ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->material?->primaryPfep?->part_type ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->material?->primaryPfep?->uloc ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->material?->primaryPfep?->pull_type ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->material?->primaryPfep?->line_side ?? 'N/A' }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ number_format($stock->qty, 2) }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $stock->material?->unit ?? 'N/A' }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center p-3">No warehouse stock found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
            <form action="{{ route('warehouse-stock.index') }}" method="GET" class="w-auto">
                <select name="per_page" class="form-select ps-2" onchange="this.form.submit()">
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 per page</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 per page</option>
                    <option value="75" {{ $perPage == 75 ? 'selected' : '' }}>75 per page</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 per page</option>
                </select>
            </form>
            {{ $stocks->links() }}
        </div>
    </div>
@endsection