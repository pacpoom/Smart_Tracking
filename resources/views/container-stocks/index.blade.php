@extends('layouts.app')

@section('title', 'Container Stock')

@section('content')
    <div class="card">
        {{-- ================= HEADER & SEARCH FORM ================= --}}
        <div class="card-header pb-0">
            <h5 class="mb-3">Container Stock</h5>

            <form action="{{ route('container-stocks.index') }}" method="GET">
                <div class="row align-items-center g-3 mb-3">

                    {{-- Plan or Container Search --}}
                    <div class="col-md-3">
                        <div class="input-group input-group-static">
                            <label>Plan or Container</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Check-in Date Filter --}}
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-6">
                                <div class="input-group input-group-static">
                                    <label>Check-in From</label>
                                    <input type="date" class="form-control" name="checkin_date_from"
                                        value="{{ request('checkin_date_from') }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group input-group-static">
                                    <label>Check-in To</label>
                                    <input type="date" class="form-control" name="checkin_date_to"
                                        value="{{ request('checkin_date_to') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detention Filter --}}
                    <div class="col-md-2">
                        <div class="input-group input-group-static">
                            <label>Detention (Days)</label>
                            <input type="number" class="form-control" name="detention_days" placeholder=">="
                                value="{{ request('detention_days') }}">
                        </div>
                    </div>

                    {{-- Control Buttons --}}
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm mb-0 me-2">Search</button>
                        @can('export container stock')
                            <button type="submit" name="export" value="1"
                                class="btn btn-success btn-sm mb-0 me-2">Export</button>
                        @endcan
                        <a href="{{ route('container-stocks.index') }}" class="btn btn-secondary btn-sm mb-0">Clear</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- ================= TABLE & DATA ================= --}}
        <div class="card-body px-0 pt-0 pb-2">
            <div class="p-4">
                @include('layouts.partials.alerts')
            </div>
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Plan No.
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Original
                                Container No.</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Current
                                Container No.</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">House BL.
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Model</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Owner /
                                Rental</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Agent</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Depot</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Current
                                Location</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Stock Status</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ETA
                                Date</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Check-in Date</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Detention</th>

                            {{-- ======== หัวตารางใหม่ที่เพิ่มเข้ามา ======== --}}
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Expired Date</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Aging Date</th>
                            {{-- ========================================== --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $stock)
                            <tr>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">
                                        {{ $stock->containerOrderPlan?->plan_no ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">
                                        {{ $stock->containerOrderPlan?->container?->container_no ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">
                                        {{ $stock->Container?->container_no ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">
                                        {{ $stock->containerOrderPlan?->house_bl ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">
                                        {{ $stock->containerOrderPlan?->model ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">
                                        {{ $stock->containerOrderPlan?->type ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">
                                        {{ isset($stock->Container->container_owner) ? ($stock->Container->container_owner == 0 ? 'Rental' : 'Owner') : 'N/A' }}
                                    </p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->Container?->agent ?? 'N/A' }}
                                    </p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->Container?->depot ?? 'N/A' }}
                                    </p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">
                                        {{ $stock->yardLocation?->location_code ?? 'N/A' }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    @if ($stock->status == 1)
                                        <span class="badge badge-sm bg-gradient-primary">Full</span>
                                    @elseif($stock->status == 2)
                                        <span class="badge badge-sm bg-gradient-warning">Partial</span>
                                    @elseif($stock->status == 3)
                                        <span class="badge badge-sm bg-gradient-secondary">Empty</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-light">N/A</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center"><span
                                        class="text-secondary text-xs font-weight-bold">{{ $stock->containerOrderPlan?->eta_date?->format('d/m/Y') ?? 'N/A' }}</span>
                                </td>
                                <td class="align-middle text-center"><span
                                        class="text-secondary text-xs font-weight-bold">{{ $stock->checkin_date?->format('d/m/Y') }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    @if (isset($stock->Container->container_owner) && $stock->Container->container_owner == 0)
                                        <span
                                            class="text-secondary text-xs font-weight-bold">{{ $stock->containerOrderPlan?->free_time ?? 'N/A' }}
                                            days</span>
                                    @else
                                        <span class="text-secondary text-xs font-weight-bold">N/A</span>
                                    @endif
                                </td>

                                {{-- ======== ข้อมูลใหม่ที่เพิ่มเข้ามา ======== --}}
                                <td class="align-middle text-center">
                                    <span
                                        class="text-secondary text-xs font-weight-bold">{{ $stock->expired_date ? $stock->expired_date->format('d/m/Y') : 'N/A' }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $stock->aging_days }}
                                        days</span>
                                </td>
                                {{-- ======================================= --}}
                            </tr>
                        @empty
                            <tr>
                                {{-- ปรับ colspan เป็น 16 เพื่อให้ครอบคลุมคอลัมน์ใหม่ --}}
                                <td colspan="16" class="text-center p-3">No containers currently in stock.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================= FOOTER & PAGINATION ================= --}}
        <div class="card-footer d-flex justify-content-between">
            {{ $stocks->withQueryString()->links() }}
        </div>
    </div>
@endsection
