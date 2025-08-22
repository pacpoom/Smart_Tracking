<!-- Picking Modal -->
<div class="modal fade" id="pickingModal" tabindex="-1" aria-labelledby="pickingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pickingModalLabel">Container Picking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="pickingForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p><strong>Container No:</strong> <span id="modalContainerNo"></span></p>
                    
                    <div class="input-group input-group-static my-4">
                        <label>Picking Date</label>
                        <input type="date" class="form-control" name="picking_date" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="input-group input-group-static my-4">
                        <label for="final_location_selector" class="ms-0">Final Location</label>
                        <select class="form-control" id="final_location_selector" name="final_location_id" required style="width: 100%;">
                            <!-- Options will be loaded by Select2 -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm Picking</button>
                </div>
            </form>
        </div>
    </div>
</div>
