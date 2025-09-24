@extends('layouts.app')

@section('title', 'Create Production Plan')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Create Production Plan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('production-plans.store') }}" method="POST" id="plan-form">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">VC Code</label>
                    <select class="form-control" id="vc_master_id" name="vc_master_id" required>
                        <option value="">-- Select VC Code --</option>
                        @foreach($vc_masters as $vc)
                            <option value="{{ $vc->id }}">{{ $vc->vc_code }} - {{ $vc->model }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Production Order</label>
                    <div class="input-group input-group-outline">
                        <input type="number" class="form-control" id="production_order" name="production_order" value="1" min="1" required>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Production Date</label>
                    <div class="input-group input-group-outline">
                        <input type="date" class="form-control" name="production_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
            </div>

            <hr class="horizontal dark">

            <h6 class="mb-3">Material Requirements</h6>
            <div class="table-responsive">
                <table class="table table-bordered align-items-center mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Material Number</th>
                            <th>Material Name</th>
                            <th>Model</th>
                            <th>BOM Qty</th>
                            <th>Required Qty</th>
                            <th>Stock Qty</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody id="bom-details-body">
                        <tr>
                            <td colspan="7" class="text-center p-4">Please select a VC Code to see material requirements.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save Plan</button>
                <a href="{{ route('production-plans.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const vcSelect = document.getElementById('vc_master_id');
    const productionOrderInput = document.getElementById('production_order');
    const bomBody = document.getElementById('bom-details-body');

    function fetchBomDetails() {
        const vcCodeId = vcSelect.value;
        const productionOrder = productionOrderInput.value;

        if (!vcCodeId) {
            bomBody.innerHTML = '<tr><td colspan="7" class="text-center p-4">Please select a VC Code to see material requirements.</td></tr>';
            return;
        }
        
        bomBody.innerHTML = '<tr><td colspan="7" class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

        fetch(`{{ route('production-plans.getBom') }}?vc_code_id=${vcCodeId}&production_order=${productionOrder}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            bomBody.innerHTML = '';
            if (data.length === 0) {
                bomBody.innerHTML = '<tr><td colspan="7" class="text-center p-4">No BOM found for this VC Code.</td></tr>';
                return;
            }
            data.forEach(item => {
                const balanceClass = item.balance < 0 ? 'text-danger fw-bold' : 'text-success';
                const row = `
                    <tr>
                        <td>${item.material_number}</td>
                        <td>${item.material_name}</td>
                        <td>${item.model}</td>
                        <td class="text-end">${item.bom_qty}</td>
                        <td class="text-end">${item.required_qty}</td>
                        <td class="text-end">${item.stock_qty}</td>
                        <td class="text-end ${balanceClass}">${item.balance}</td>
                    </tr>
                `;
                bomBody.innerHTML += row;
            });
        })
        .catch(error => {
            console.error('Error fetching BOM:', error);
            bomBody.innerHTML = '<tr><td colspan="7" class="text-center p-4 text-danger">Failed to load data.</td></tr>';
        });
    }

    vcSelect.addEventListener('change', fetchBomDetails);
    productionOrderInput.addEventListener('change', fetchBomDetails);
    
    // For Select2 initialization if you use it
    $('#vc_master_id').select2({
        theme: 'bootstrap-5'
    });
});
</script>
@endpush
