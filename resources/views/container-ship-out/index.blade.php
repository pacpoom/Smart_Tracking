@extends('layouts.app')

@section('title', 'Container Ship Out')

@push('styles')
{{-- 1. เพิ่ม CSS สำหรับ Blinking Animation --}}
<style>
    @keyframes blinker {
        50% {
            opacity: 0.3;
        }
    }
    .blinking-indicator {
        animation: blinker 1.5s linear infinite;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Container Ship Out</h5>
            <form action="{{ route('container-ship-out.index') }}" method="GET" class="w-100 w-md-auto">
                <div class="input-group input-group-outline">
                    <label class="form-label">Search by Container No...</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        @include('layouts.partials.alerts')
        
        {{-- Card-based layout for mobile friendliness --}}
        <div class="row">
            @forelse ($pullingPlans as $plan)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border">
                        <div class="card-header border-bottom pb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ $plan->containerOrderPlan?->container?->container_no ?? 'N/A' }}</h6>
                                
                                {{-- 2. เพิ่ม Logic การแสดง Plan Type --}}
                                @if($plan->plan_type === 'All')
                                    <span class="badge bg-gradient-success blinking-indicator">All</span>
                                @elseif($plan->plan_type === 'Pull')
                                    <span class="badge bg-gradient-info blinking-indicator">Pull</span>
                                @endif
                            </div>
                            <p class="text-sm mb-0">Order: {{ $plan->pulling_order ?? 'N/A' }}</p>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Location:</strong> {{ $plan->containerOrderPlan?->containerStock?->yardLocation?->location_code ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Plan No:</strong> {{ $plan->pulling_plan_no }}</p>
                            <p class="mb-0"><strong>Pulling Date:</strong> {{ $plan->pulling_date?->format('d/m/Y') }}</p>
                        </div>
                        <div class="card-footer pt-0">
                            <button type="button" class="btn btn-dark w-100 mb-0" data-bs-toggle="modal" data-bs-target="#shipOutModal-{{ $plan->id }}">
                                Ship Out
                            </button>
                        </div>
                    </div>
                </div>
                {{-- Modal remains the same --}}
                @include('container-ship-out.partials.ship-out-modal', ['plan' => $plan])
            @empty
                <div class="col-12">
                    <p class="text-center p-3">No containers with a pulling plan found.</p>
                </div>
            @endforelse
        </div>
    </div>
    <div class="card-footer d-flex justify-content-center">
        {{ $pullingPlans->withQueryString()->links() }}
    </div>
</div>
@endsection
