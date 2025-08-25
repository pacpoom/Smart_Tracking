<div class="modal fade" id="shipOutModal-{{ $plan->id }}" tabindex="-1" aria-labelledby="shipOutModalLabel-{{ $plan->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('container-open.shipOut', $plan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="shipOutModalLabel-{{ $plan->id }}">Confirm Open for {{ $plan->containerOrderPlan?->container?->container_no ?? 'N/A' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Departure Date</label>
                            <div class="input-group input-group-outline">
                                <input type="date" class="form-control" name="departure_date" value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pulling Type</label>
                            <div class="input-group input-group-outline">
                                <select class="form-control" name="plan_type" required>
                                    <option value="pull" {{ $plan->plan_type == 'pull' ? 'selected' : '' }}>Pull</option>
                                    <option value="all" {{ $plan->plan_type == 'all' ? 'selected' : '' }}>All</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Final Location (Optional)</label>
                        <select class="form-control location-select" name="new_yard_location_id" style="width: 100%;"></select>
                        <p class="text-xs text-muted mt-1">Select a new location if the container is moved before shipping out (e.g., to a gate).</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <div class="input-group input-group-outline">
                            <textarea class="form-control" name="remarks" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Confirm Ship Out</button>
                </div>
            </form>
        </div>
    </div>
</div>
