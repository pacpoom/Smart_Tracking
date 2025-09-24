@csrf
<div class="row">
    <div class="col-md-4 mb-3">
        <label for="vc_master_id" class="form-label">VC Code</label>
        <select class="form-control" id="vc_master_id" name="vc_master_id" required>
            <option value="">-- Select a VC Code --</option>
            @foreach($vcMasters as $vcMaster)
                <option value="{{ $vcMaster->id }}" data-model="{{ $vcMaster->model }}" {{ (old('vc_master_id', $productionPlan->vc_master_id ?? '') == $vcMaster->id) ? 'selected' : '' }}>
                    {{ $vcMaster->vc_code }}
                </option>
            @endforeach
        </select>
        @error('vc_master_id')
            <p class="text-danger text-xs pt-1"> {{ $message }} </p>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label for="production_order" class="form-label">Production Order (Qty)</label>
        <div class="input-group input-group-outline">
            <input type="number" class="form-control" id="production_order" name="production_order"
                value="{{ old('production_order', $productionPlan->production_order ?? '') }}" required min="1">
        </div>
         @error('production_order')
            <p class="text-danger text-xs pt-1"> {{ $message }} </p>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label for="production_date" class="form-label">Production Date</label>
        <div class="input-group input-group-outline">
            <input type="date" class="form-control" id="production_date" name="production_date"
                   value="{{ old('production_date', isset($productionPlan) ? $productionPlan->production_date->format('Y-m-d') : '') }}" required>
        </div>
         @error('production_date')
            <p class="text-danger text-xs pt-1"> {{ $message }} </p>
        @enderror
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const vcSelect = document.getElementById('vc_master_id');
        const orderInput = document.getElementById('production_order');
        const materialsContainer = document.getElementById('materials-container');
        const materialsTable = document.getElementById('materials-table');
        const materialsTbody = document.getElementById('materials-tbody');
        const placeholderText = document.getElementById('placeholder-text');

        function fetchBomDetails() {
            const vcMasterId = vcSelect.value;
            const productionOrder = orderInput.value;

            if (!vcMasterId || !productionOrder || productionOrder < 1) {
                materialsTable.classList.add('d-none');
                placeholderText.classList.remove('d-none');
                materialsTbody.innerHTML = '';
                return;
            }
            
            // Show loading state
            placeholderText.textContent = 'Loading materials...';
            materialsTable.classList.add('d-none');
            placeholderText.classList.remove('d-none');

            fetch(`{{ url('/api/boms-by-vc') }}/${vcMasterId}?production_order=${productionOrder}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    materialsTbody.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const isSufficient = item.stock_qty >= item.required_qty;
                            const statusBadge = isSufficient 
                                ? '<span class="badge bg-gradient-success">Sufficient</span>' 
                                : '<span class="badge bg-gradient-danger">Insufficient</span>';
                            
                            const row = `
                                <tr>
                                    <td><p class="text-xs font-weight-bold mb-0">${item.material_number}</p></td>
                                    <td><p class="text-xs font-weight-bold mb-0">${item.material_name}</p></td>
                                    <td class="text-end"><p class="text-xs font-weight-bold mb-0">${parseFloat(item.bom_qty).toFixed(3)}</p></td>
                                    <td class="text-end"><p class="text-xs font-weight-bold mb-0">${parseFloat(item.required_qty).toFixed(3)}</p></td>
                                    <td class="text-end"><p class="text-xs font-weight-bold mb-0">${parseFloat(item.stock_qty).toFixed(3)}</p></td>
                                    <td class="text-center">${statusBadge}</td>
                                </tr>
                            `;
                            materialsTbody.insertAdjacentHTML('beforeend', row);
                        });
                        materialsTable.classList.remove('d-none');
                        placeholderText.classList.add('d-none');
                    } else {
                        placeholderText.textContent = 'No Bill of Materials (BOM) found for the selected VC Code.';
                    }
                })
                .catch(error => {
                    console.error('Error fetching BOM details:', error);
                    placeholderText.textContent = 'Failed to load material details. Please try again.';
                });
        }

        vcSelect.addEventListener('change', fetchBomDetails);
        orderInput.addEventListener('input', fetchBomDetails);

        // Initial fetch if values are pre-filled (on edit page)
        if (vcSelect.value && orderInput.value) {
            fetchBomDetails();
        }

        // Initialize Select2
        $(vcSelect).select2({
            theme: "bootstrap-5",
            placeholder: $(this).data('placeholder'),
        });
    });
</script>
@endpush