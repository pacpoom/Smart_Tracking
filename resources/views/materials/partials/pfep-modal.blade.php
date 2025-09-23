{{-- ========================================================================= --}}
{{-- PFEP Details Modal --}}
{{-- ========================================================================= --}}
<div class="modal fade" id="pfepModal-{{ $material->id }}" tabindex="-1"
    aria-labelledby="pfepModalLabel-{{ $material->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pfepModalLabel-{{ $material->id }}">PFEP Details for:
                    <strong>{{ $material->material_number }}</strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($material->pfeps->isNotEmpty())
                    <div class="list-group">
                        @foreach ($material->pfeps as $pfep)
                            <div class="list-group-item mb-3 border rounded-3 shadow-sm p-3">
                                <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 text-dark">Model: {{ $pfep->model ?? 'N/A' }}</h6>
                                    <div>
                                        @if ($pfep->is_primary)
                                            <span class="badge bg-gradient-success me-2"><i
                                                    class="fas fa-check-circle me-1"></i> Primary</span>
                                        @else
                                            <span class="badge bg-gradient-secondary me-2">Secondary</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-3 col-6">
                                        <small class="text-muted d-block">Part Type</small>
                                        <strong>{{ $pfep->part_type ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <small class="text-muted d-block">Uloc</small>
                                        <strong>{{ $pfep->uloc ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <small class="text-muted d-block">Pull Type</small>
                                        <strong>{{ $pfep->pull_type ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <small class="text-muted d-block">Line Side</small>
                                        <strong>{{ $pfep->line_side ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                                <div class="mt-3 text-end">
                                    {{-- ✅ FIX: Changed form to use POST method for setting primary --}}
                                    @if (!$pfep->is_primary)
                                        <form action="{{ route('pfeps.setPrimary', $pfep->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm mb-0">Set as
                                                Primary</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('pfeps.edit', $pfep->id) }}"
                                        class="btn btn-outline-info btn-sm mb-0">Edit</a>
                                    <button type="button" class="btn btn-outline-danger btn-sm mb-0"
                                        data-bs-toggle="modal" data-bs-target="#deletePfepModal-{{ $pfep->id }}">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No PFEP records found for this material.</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('pfeps.create', ['material_id' => $material->id]) }}" class="btn btn-primary mb-0">
                    <i class="fas fa-plus me-1"></i> Add New PFEP
                </a>
            </div>
        </div>
    </div>
</div>


{{-- ========================================================================= --}}
{{-- PFEP Delete Confirmation Modals (one for each PFEP item) --}}
{{-- ========================================================================= --}}
@foreach ($material->pfeps as $pfep)
    <div class="modal fade" id="deletePfepModal-{{ $pfep->id }}" tabindex="-1"
        aria-labelledby="deletePfepModalLabel-{{ $pfep->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePfepModalLabel-{{ $pfep->id }}">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the PFEP for model '<strong>{{ $pfep->model }}</strong>'?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('pfeps.destroy', $pfep->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Confirm Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach