@extends('layouts.app')

@section('title', 'Change Container Location')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Change Container Location</h5>
            <form action="{{ route('container-change-location.index') }}" method="GET" class="md-2">
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
                            <button type="button" class="btn btn-sm btn-outline-dark mb-0" data-bs-toggle="modal" data-bs-target="#changeLocationModal-{{ $stock->id }}">
                                Change Location
                            </button>
                        </td>
                    </tr>
                    @include('container-change-location.partials.change-location-modal', ['stock' => $stock])
                    @empty
                    <tr><td colspan="4" class="text-center p-3">No containers in stock to move.</td></tr>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Use event delegation for modals that are created in a loop
    document.body.addEventListener('show.bs.modal', function(event) {
        let modal = event.target;
        // Check if it's our change location modal
        if (modal.id.startsWith('changeLocationModal-')) {
            let selectElement = modal.querySelector('.location-select');

            // Check if the modal has a select element and if it's not already initialized
            if (selectElement && !$(selectElement).data('select2')) {
                let current_location_id = $(selectElement).data('current-location-id');

                $(selectElement).select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $(modal), // Important for modals
                    placeholder: 'Type to search for a location...',
                    ajax: {
                        url: '{{ route("yard-locations.search") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term, // search term
                                exclude: current_location_id // pass the current location to exclude
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
