<div class="modal fade" id="shipOutModal-{{ $stock->id }}" tabindex="-1" aria-labelledby="shipOutModalLabel-{{ $stock->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('container-ship-out.shipOut', $stock->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="shipOutModalLabel-{{ $stock->id }}">Confirm Ship Out for {{ $stock->containerOrderPlan->container->container_no }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Departure Date</label>
                        <div class="input-group input-group-outline">
                            <input type="date" class="form-control" name="departure_date" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
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
