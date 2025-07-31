<div class="modal fade" id="returnModal-{{ $stock->id }}" tabindex="-1" aria-labelledby="returnModalLabel-{{ $stock->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('container-return.return', $stock->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel-{{ $stock->id }}">Confirm Return for {{ $stock->containerOrderPlan?->container?->container_no ?? 'N/A' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to return this container? This action will remove it from the stock and cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Confirm Return</button>
                </div>
            </form>
        </div>
    </div>
</div>
