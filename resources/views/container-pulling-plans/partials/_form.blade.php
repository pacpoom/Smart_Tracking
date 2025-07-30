@csrf
<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Select Container in Stock</label>
        <select class="form-control" id="plan-select" name="container_order_plan_id" required>
            {{-- Pre-populate for edit form --}}
            @if(isset($containerPullingPlan) && $containerPullingPlan->containerOrderPlan)
                <option value="{{ $containerPullingPlan->container_order_plan_id }}" selected>
                    {{ $containerPullingPlan->containerOrderPlan->container->container_no }} (Plan: {{ $containerPullingPlan->containerOrderPlan->plan_no }})
                </option>
            @endif
        </select>
        @error('container_order_plan_id') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Plan Type</label>
        <div class="input-group input-group-outline">
            <select class="form-control" name="plan_type" required>
                <option value="All" {{ (old('plan_type', $containerPullingPlan->plan_type ?? 'All') == 'All') ? 'selected' : '' }}>All</option>
                <option value="Pull" {{ (old('plan_type', $containerPullingPlan->plan_type ?? '') == 'Pull') ? 'selected' : '' }}>Pull</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Pulling Date</label>
        <div class="input-group input-group-outline">
            <input type="date" class="form-control" name="pulling_date" value="{{ old('pulling_date', isset($containerPullingPlan) ? $containerPullingPlan->pulling_date?->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
        </div>
        @error('pulling_date') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Destination</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="destination" value="{{ old('destination', $containerPullingPlan->destination ?? '') }}">
        </div>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Remarks</label>
    <div class="input-group input-group-outline">
        <textarea class="form-control" name="remarks" rows="3">{{ old('remarks', $containerPullingPlan->remarks ?? '') }}</textarea>
    </div>
</div>

@if(isset($containerPullingPlan))
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Status</label>
            <div class="input-group input-group-outline">
                <select class="form-control" name="status" required>
                    <option value="1" {{ (old('status', $containerPullingPlan->status ?? 1) == 1) ? 'selected' : '' }}>Planned</option>
                    <option value="2" {{ (old('status', $containerPullingPlan->status ?? 1) == 2) ? 'selected' : '' }}>In Progress</option>
                    <option value="3" {{ (old('status', $containerPullingPlan->status ?? 1) == 3) ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Pulling Order</label>
            <div class="input-group input-group-outline">
                <input type="number" class="form-control" name="pulling_order" value="{{ old('pulling_order', $containerPullingPlan->pulling_order ?? '') }}" required>
            </div>
            @error('pulling_order') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
        </div>
    </div>
@endif

@push('scripts')
<script>
    $(document).ready(function() {
        $('#plan-select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Search by Plan No, Container No, or B/L...',
            ajax: {
                url: '{{ route("container-order-plans.searchStock") }}',
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
