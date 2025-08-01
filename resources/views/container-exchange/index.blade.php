@extends('layouts.app')

@section('title', 'Container Exchange History')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        {{-- แก้ไข: เพิ่ม d-flex เพื่อจัดวางองค์ประกอบ --}}
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Container Exchange History</h5>
            {{-- เพิ่มปุ่ม Create New Exchange --}}
            @can('exchange containers')
                <a href="{{ route('container-exchange.create') }}" class="btn btn-dark mb-0">Create New Exchange</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        {{-- Search Form --}}
        <form action="{{ route('container-exchange.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Search by Container No.</label>
                    <div class="input-group input-group-outline">
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <div class="input-group input-group-outline">
                        <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <div class="input-group input-group-outline">
                        <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100">Search</button>
                </div>
            </div>
        </form>

        <div class="table-responsive p-0 mt-4">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Source Container</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Destination Container</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">User</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Exchange Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($exchanges as $exchange)
                    <tr>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $exchange->sourceStock?->containerOrderPlan?->container?->container_no ?? 'N/A' }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $exchange->destinationStock?->containerOrderPlan?->container?->container_no ?? 'N/A' }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $exchange->user?->name ?? 'N/A' }}</p></td>
                        <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $exchange->exchange_date?->format('d/m/Y H:i') }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center p-3">No exchange history found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        {{ $exchanges->withQueryString()->links() }}
    </div>
</div>
@endsection
