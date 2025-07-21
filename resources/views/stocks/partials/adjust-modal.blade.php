@can('adjust stock')
{{-- แก้ไข: เปลี่ยน ID ของ Modal --}}
<div class="modal fade" id="adjustModal-{{ $part->id }}" tabindex="-1" aria-labelledby="adjustModalLabel-{{ $part->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- แก้ไข: เปลี่ยน action ของ form --}}
            <form action="{{ route('stocks.adjust', $part->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="adjustModalLabel-{{ $part->id }}">Adjust Stock for {{ $part->part_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- แก้ไข: แสดง Current Quantity --}}
                    <p>Current Quantity: <strong>{{ $part->stock?->qty ?? 0 }}</strong></p>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Action</label>
                            <div class="input-group input-group-outline">
                                <select class="form-control" name="action" required>
                                    <option value="add">Add (+)</option>
                                    <option value="subtract">Subtract (-)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Adjustment Quantity</label>
                            <div class="input-group input-group-outline">
                                <input type="number" class="form-control" name="adjustment_qty" min="1" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Adjust Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
