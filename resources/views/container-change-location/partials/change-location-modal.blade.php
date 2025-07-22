<div class="modal fade" id="changeLocationModal-{{ $stock->id }}" tabindex="-1" aria-labelledby="changeLocationModalLabel-{{ $stock->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('container-change-location.update', $stock->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="changeLocationModalLabel-{{ $stock->id }}">Change Location for {{ $stock->containerOrderPlan->container->container_no }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Current Location: <strong>{{ $stock->yardLocation->location_code ?? 'N/A' }}</strong></p>
                    <div class="mb-3">
                        <label class="form-label">New Location</label>
                        {{-- 1. เปลี่ยนเป็น Searchable Select2 dropdown --}}
                        <select class="form-control location-select" name="new_yard_location_id" required style="width: 100%;"
                                data-current-location-id="{{ $stock->yard_location_id }}">
                            {{-- Options will be loaded via AJAX --}}
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
