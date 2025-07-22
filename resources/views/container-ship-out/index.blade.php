@extends('layouts.app')

@section('title', 'Container Ship Out')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Container Ship Out</h5>
            <form action="{{ route('container-ship-out.index') }}" method="GET" class="md-2">
                <div class="input-group input-group-outline">
                    <label class="form-label">Search by Container No...</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                </div>
            </form>
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
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Container No.</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Size</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Current Location</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stocks as $stock)
                    <tr>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->containerOrderPlan->container->container_no }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->containerOrderPlan->container->size }}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0 px-2">{{ $stock->yardLocation->location_code ?? 'N/A' }}</p></td>
                        <td class="align-middle text-center">
                            <button type="button" class="btn btn-sm btn-dark mb-0" data-bs-toggle="modal" data-bs-target="#shipOutModal-{{ $stock->id }}">
                                Ship Out
                            </button>
                        </td>
                    </tr>
                    @include('container-ship-out.partials.ship-out-modal', ['stock' => $stock])
                    @empty
                    <tr><td colspan="5" class="text-center p-3">No containers in stock to ship out.</td></tr>
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
