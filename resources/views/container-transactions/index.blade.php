@extends('layouts.app')

@section('title', 'Container Transaction Log')

@section('content')
<div class="card">
    {{-- <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Container Transaction Log</h5>
            @can('export container transactions')
                <a href="{{ route('container-transactions.export', request()->query()) }}" class="btn btn-success mb-0">Export</a>
            @endcan
        </div>
    </div> --}}
    <div class="card-body">
        {{-- Search Form --}}
        <form action="{{ route('container-transactions.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
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
                    <button type="submit" class="btn btn-dark w-80">Search</button>
                </div>
                <div class="col-md-2">
                    @can('export container transactions')
                        <a href="{{ route('container-transactions.export', request()->query()) }}" class="btn btn-success w-80">Export</a>
                    @endcan
                </div>
            </div>
        </div>
        </form>
        <div class="table-responsive p-0 mt-4">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Container No.</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">House B/L</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Activity</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">User</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Remarks</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                    <tr>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $transaction->containerOrderPlan?->container?->container_no ?? 'N/A' }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $transaction->containerOrderPlan?->house_bl }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $transaction->activity_type }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $transaction->yardLocation?->location_code ?? 'N/A' }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $transaction->user->name }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $transaction->remarks ?? 'N/A' }}</p></td>
                        <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $transaction->transaction_date->format('d/m/Y H:i') }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center p-3">No transactions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        {{ $transactions->withQueryString()->links() }}
    </div>
</div>
@endsection
