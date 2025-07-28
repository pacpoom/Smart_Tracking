@can('delete container tackings')
<div class="modal fade" id="deleteModal-{{ $tacking->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $tacking->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel-{{ $tacking->id }}">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the tacking record for container '<strong>{{ $tacking->containerOrderPlan?->container?->container_no ?? 'N/A' }}</strong>'?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('container-tacking.destroy', $tacking->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
