<div class="modal fade" id="deleteModal-{{ $material->id }}" tabindex="-1"
    aria-labelledby="deleteModalLabel-{{ $material->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel-{{ $material->id }}">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the material '<strong>{{ $material->material_number }} -
                    {{ $material->material_name }}</strong>'?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                {{-- ฟอร์มนี้จะทำงานเมื่อกดยืนยัน --}}
                <form action="{{ route('materials.destroy', $material->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
