@extends('layouts.app')

@section('title', 'Change Container Location')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Change Container Location</h5>
            <form action="{{ route('container-change-location.index') }}" method="GET" class="w-100 w-md-auto">
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
                            <button type="button" class="btn btn-dark w-100 mb-0" data-bs-toggle="modal" data-bs-target="#changeLocationModal-{{ $stock->id }}">
                                Change Location
                            </button>
                        </div>
                    </div>
                </div>
                @include('container-change-location.partials.change-location-modal', ['stock' => $stock])
            @empty
                <div class="col-12">
                    <p class="text-center p-3">No containers in stock to move.</p>
                </div>
            @endforelse
        </div>
    </div>
    <div class="card-footer d-flex justify-content-center">
        {{-- Pagination --}}
        {{ $stocks->withQueryString()->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('show.bs.modal', function(event) {
        let modal = event.target;
        if (modal.id.startsWith('changeLocationModal-')) {
            let selectElement = modal.querySelector('.location-select');
            if (selectElement && !$(selectElement).data('select2')) {
                let current_location_id = $(selectElement).data('current-location-id');
                $(selectElement).select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $(modal),
                    placeholder: 'Type to search for a location...',
                    ajax: {
                        url: '{{ route("yard-locations.search") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term,
                                exclude: current_location_id
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });
            }
        }
    });
});
</script>
@endpush
