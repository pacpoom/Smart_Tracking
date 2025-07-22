@extends('layouts.app')

@section('title', 'Container Stock')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Container Stock</h5>
            <div class="d-flex align-items-center">
                <form action="{{ route('container-stocks.index') }}" method="GET" class="me-2">
                    <div class="input-group input-group-outline">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                    </div>
                </form>
                @can('export container stock')
                    <a href="{{ route('container-stocks.export', request()->query()) }}" class="btn btn-success mb-0">Export</a>
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
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Plan No.</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Container No.</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">House BL</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Size</th>
                        {{-- เพิ่มคอลัมน์ใหม่ --}}
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Model</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Current Location</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Check-in Date</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ETA Date</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Expiration Date</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Remaining Free Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stocks as $stockPlan)
                    <tr>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stockPlan->plan_no }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stockPlan->container->container_no }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stockPlan->house_bl }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stockPlan->container->size }}</p></td>
                        {{-- แสดงข้อมูลใหม่ --}}
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stockPlan->model }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stockPlan->type }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stockPlan->containerStock->yardLocation->location_code ?? 'N/A' }}</p></td>
                        <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $stockPlan->checkin_date?->format('d/m/Y') }}</span></td>
                        <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $stockPlan->eta_date?->format('d/m/Y') }}</span></td>
                        <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $stockPlan->expiration_date?->format('d/m/Y') }}</span></td>
                        <td class="align-middle text-center">
                            @if($stockPlan->remaining_free_time === 'Expired')
                                <span class="badge badge-sm bg-gradient-danger">Expired</span>
                            @else
                                <span class="text-secondary text-xs font-weight-bold">{{ $stockPlan->remaining_free_time }} days</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    {{-- แก้ไข colspan --}}
                    <tr><td colspan="10" class="text-center p-3">No containers currently in stock.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        {{ $stocks->withQueryString()->links() }}
    </div>
</div>
@endsection
