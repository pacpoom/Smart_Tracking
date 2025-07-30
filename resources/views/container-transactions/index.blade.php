@extends('layouts.app')

@section('title', 'Container Transaction Log')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Container Transaction Log</h5>
            <form action="{{ route('container-transactions.index') }}" method="GET" class="md-2">
                <div class="input-group input-group-outline">
                    <label class="form-label">Search by Container No</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Container No.</th>
                        {{-- เพิ่มคอลัมน์นี้ --}}
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">House B/L</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Activity</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Remarks</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">User</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                    <tr>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $transaction->containerOrderPlan->container->container_no }}</p></td>
                        {{-- แสดงข้อมูล House B/L จาก Relationship --}}
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $transaction->containerOrderPlan->house_bl }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $transaction->activity_type }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $transaction->yardLocation?->location_code ?? 'N/A' }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $transaction->remarks }}</p></td>  
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $transaction->user->name }}</p></td>
                        <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $transaction->transaction_date->format('d/m/Y H:i') }}</span></td>
                    </tr>
                    @empty
                    {{-- แก้ไข colspan --}}
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
