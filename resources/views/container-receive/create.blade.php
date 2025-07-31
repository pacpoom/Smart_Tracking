@extends('layouts.app')

@section('title', 'Container Receive')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <form action="{{ route('container-receive.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Container Receive</h5>
                    <button type="submit" class="btn btn-dark">Receive Container</button>
                </div>
                <div class="card-body">
                    @include('layouts.partials.alerts')

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Select Container Plan (Pending)</label>
                            <select class="form-control" id="plan-select" name="container_order_plan_id" required></select>
                            @error('container_order_plan_id') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Select Yard Location (Available)</label>
                            <select class="form-control" id="location-select" name="yard_location_id" required></select>
                             @error('yard_location_id') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Check-in Date</label>
                            <div class="input-group input-group-outline">
                                <input type="date" class="form-control" name="checkin_date" value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                             @error('checkin_date') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <div class="input-group input-group-outline">
                            <textarea class="form-control" name="remarks" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 for Container Plan with AJAX
        $('#plan-select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Search by Plan No, Container No, or B/L...',
            ajax: {
                url: '{{ route("container-order-plans.search") }}', // This route should search for status != 3
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        // Initialize Select2 for Yard Location with AJAX
        $('#location-select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Search for an available location...',
            ajax: {
                url: '{{ route("yard-locations.search") }}', // This route searches for available locations
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });
</script>
@endpush
