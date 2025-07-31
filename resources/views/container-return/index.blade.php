@extends('layouts.app')

@section('title', 'Container Return')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Container Return (Empty Containers)</h5>
            <form action="{{ route('container-return.index') }}" method="GET" class="w-100 w-md-auto">
                <div class="input-group input-group-outline">
                    <label class="form-label">Search by Container No...</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        @include('layouts.partials.alerts')
        
        <div class="row">
            @forelse ($stocks as $stock)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border">
                        <div class="card-header border-bottom pb-2">
                            <h6 class="mb-0">{{ $stock->containerOrderPlan?->container?->container_no ?? 'N/A' }}</h6>
                            <p class="text-sm mb-0">Size: {{ $stock->containerOrderPlan?->container?->size ?? 'N/A' }}</p>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Current Location:</strong></p>
                            <h5 class="font-weight-bolder">{{ $stock->yardLocation?->location_code ?? 'N/A' }}</h5>
                        </div>
                        <div class="card-footer pt-0">
                            <button type="button" class="btn btn-dark w-100 mb-0" data-bs-toggle="modal" data-bs-target="#returnModal-{{ $stock->id }}">
                                Return Container
                            </button>
                        </div>
                    </div>
                </div>
                @include('container-return.partials.return-modal', ['stock' => $stock])
            @empty
                <div class="col-12">
                    <p class="text-center p-3">No empty containers available for return.</p>
                </div>
            @endforelse
        </div>
    </div>
    <div class="card-footer d-flex justify-content-center">
        {{ $stocks->withQueryString()->links() }}
    </div>
</div>
@endsection
