@extends('layouts.app')

@section('title', 'Current Container Stock')

@section('content')
    <div class="card">
        {{-- ================= HEADER & SEARCH FORM ================= --}}
        <div class="card-header pb-0">
            <h5 class="mb-3">Current Container Stock</h5>

            <form action="{{ route('container-stocks.by-current') }}" method="GET">
                <div class="row align-items-center g-3 mb-3">
                    {{-- Container or Location Search --}}
                    <div class="col-md-4">
                        <div class="input-group input-group-static">
                            <label>Container or Location</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Control Buttons --}}
                    <div class="col-md-8 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm mb-0 me-2">Search</button>
                        <a href="{{ route('container-stocks.by-current') }}" class="btn btn-secondary btn-sm mb-0">Clear</a>
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
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Current Container No.</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Size</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Owner / Rental</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Agent</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Depot</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Current Location</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Stock Status</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Check-in Date</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aging Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $stock)
                            <tr>
                                <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->Container?->container_no ?? 'N/A' }}</p></td>
                                <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->Container?->size ?? 'N/A' }}</p></td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">
                                        {{ isset($stock->Container->container_owner) ? ($stock->Container->container_owner == 0 ? 'Rental' : 'Owner') : 'N/A' }}
                                    </p>
                                </td>
                                <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->Container?->agent ?? 'N/A' }}</p></td>
                                <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->Container?->depot ?? 'N/A' }}</p></td>
                                <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->yardLocation?->location_code ?? 'N/A' }}</p></td>
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
                                <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $stock->checkin_date?->format('d/m/Y') }}</span></td>
                                <td class="align-middle text-center">
                                    {{-- Assuming aging_days is an accessor on the model --}}
                                    <span class="text-secondary text-xs font-weight-bold">{{ $stock->aging_days ?? 'N/A' }} days</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center p-3">No containers currently in stock.</td>
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