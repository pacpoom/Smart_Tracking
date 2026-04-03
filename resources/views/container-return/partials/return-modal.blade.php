<!-- Return Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark" id="returnModalLabel"><i class="fas fa-undo me-2"></i> Confirm Return</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                <p class="fs-5 mb-1">Are you sure you want to return container:</p>
                <h3 class="fw-bold text-primary mb-3" id="modalContainerNo"></h3>
                <p class="text-muted small">This action will mark the container as returned and remove it from the current stock location.</p>
                
                <form action="{{ route('container-return.store') }}" method="POST">
                    @csrf
                    <!-- เปลี่ยน input ที่ซ่อนไว้ให้รับ container_id แทน stock_id -->
                    <input type="hidden" name="container_id" id="returnContainerId" value="">
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-warning btn-lg fw-bold">Confirm Return</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // ฟังก์ชั่นสำหรับเปิด Modal พร้อมทั้งส่งค่า containerId และ containerNo ไปแสดง
    function openReturnModal(containerId, containerNo) {
        document.getElementById('returnContainerId').value = containerId;
        document.getElementById('modalContainerNo').textContent = containerNo;
        let returnModal = new bootstrap.Modal(document.getElementById('returnModal'));
        returnModal.show();
    }
</script>