@csrf
<div class="row">
    <div class="col-md-12 mb-3">
        <label class="form-label">Select or Create Container</label>
        <select class="form-control" id="container-select" name="container_no" required>
            {{-- Pre-populate for edit form --}}
            @if(isset($containerOrderPlan) && $containerOrderPlan->container)
                <option value="{{ $containerOrderPlan->container->container_no }}" selected>
                    {{ $containerOrderPlan->container->container_no }} - {{ $containerOrderPlan->container->size }}
                </option>
            @endif
        </select>
        @error('container_no') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Model</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="model" value="{{ old('model', $containerOrderPlan->model ?? '') }}">
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Type</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="type" value="{{ old('type', $containerOrderPlan->type ?? '') }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">House B/L</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="house_bl" value="{{ old('house_bl', $containerOrderPlan->house_bl ?? '') }}">
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Free Time (days)</label>
        <div class="input-group input-group-outline">
            <input type="number" class="form-control" name="free_time" value="{{ old('free_time', $containerOrderPlan->free_time ?? '') }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">ETA Date</label>
        <div class="input-group input-group-outline">
            <input type="date" class="form-control" name="eta_date" value="{{ old('eta_date', isset($containerOrderPlan) ? $containerOrderPlan->eta_date?->format('Y-m-d') : '') }}">
        </div>
        @error('eta_date') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Check-in Date</label>
        <div class="input-group input-group-outline">
            <input type="date" class="form-control" name="checkin_date" value="{{ old('checkin_date', isset($containerOrderPlan) ? $containerOrderPlan->checkin_date?->format('Y-m-d') : '') }}">
        </div>
        @error('checkin_date') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
    </div>
</div>

{{-- Show status only on the edit page --}}
@if(isset($containerOrderPlan))
    <div class="mb-3">
        <label class="form-label">Status</label>
        <div class="input-group input-group-outline">
            @php
                $statusText = match ($containerOrderPlan->status) {
                    1 => 'Pending',
                    2 => 'Received',
                    3 => 'Shipped Out',
                    default => 'Unknown',
                };
            @endphp
            <input type="text" class="form-control" value="{{ $statusText }}" readonly disabled>
        </div>
    </div>
@endif


@push('scripts')
<script>
    $(document).ready(function() {
        $('#container-select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select or type a new Container No...',
            tags: true,
            ajax: {
                //url: '{{ route("containers.search") }}',
                url: '/containers/search',
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
