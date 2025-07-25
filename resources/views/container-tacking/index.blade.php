@extends('layouts.app')

@section('title', 'Container Tacking List')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Container Tacking List</h5>
            <form action="{{ route('container-tacking.index') }}" method="GET" class="md-2">
                <div class="input-group input-group-outline">
                    <label class="form-label">Search by</label>
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
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Shipment / B/L</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Job Type</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">User</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tackings as $tacking)
                    <tr>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $tacking->container->container_no }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $tacking->shipment }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $tacking->job_type }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $tacking->user->name }}</p></td>
                        <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $tacking->created_at->format('d/m/Y H:i') }}</span></td>
                        <td class="align-middle text-center">
                            <a href="{{ route('container-tacking.show', $tacking->id) }}" class="btn btn-link text-secondary mb-0" title="View Details">
                                <i class="material-symbols-rounded">visibility</i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center p-3">No tacking records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        {{ $tackings->withQueryString()->links() }}
    </div>
</div>
@endsection
