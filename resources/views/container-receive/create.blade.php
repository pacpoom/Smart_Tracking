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
                            <select class="form-control" id="plan-select" name="container_order_plan_id" required>
                                <option value="">-- Choose a Pending Plan --</option>
                                @foreach($pendingPlans as $plan)
                                    <option value="{{ $plan->id }}">
                                        {{ $plan->plan_no }} | {{ $plan->container->container_no }} | B/L: {{ $plan->house_bl }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Select Yard Location</label>
                            <select class="form-control" id="location-select" name="yard_location_id" required>
                                 <option value="">-- Choose a Location --</option>
                                 @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->location_code }}</option>
                                 @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Check-in Date</label>
                            <div class="input-group input-group-outline">
                                <input type="date" class="form-control" name="checkin_date" value="{{ now()->format('Y-m-d') }}" required>
                            </div>
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
        $('#plan-select').select2({ theme: 'bootstrap-5' });
        $('#location-select').select2({ theme: 'bootstrap-5' });
    });
</script>
@endpush
