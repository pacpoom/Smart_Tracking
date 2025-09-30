@extends('layouts.app')

@section('title', 'Monitoring Plan')

@push('styles')
<style>
    /* Custom styles for Monitoring Plan inputs */
    .search-form-container .form-control,
    .search-form-container .btn {
        height: 40px; /* Consistent height for inputs and buttons */
    }

    .search-form-container input[name="plan_qty"] {
        width: 150px; /* Specific width for plan quantity */
        flex-shrink: 0; /* Prevent shrinking */
    }

    .search-form-container .form-control {
        border-radius: 0.375rem; /* 6px */
        border: 1px solid #ced4da;
        padding: 0.5rem 0.75rem; /* 8px 12px */
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        box-shadow: inset 0 1px 2px rgba(0,0,0,.075);
    }

    .search-form-container .form-control:focus {
        color: #495057;
        background-color: #fff;
        border-color: #86b7fe;
        outline: 0;
        box-shadow: inset 0 1px 2px rgba(0,0,0,.075), 0 0 0 0.25rem rgba(13, 110, 253, .25);
    }
    
    .search-form-container .form-label {
        margin-bottom: 0.5rem;
        font-weight: 300;
    }

    /* Adjust table font size for better readability */
    #monitoring-plan-table th,
    #monitoring-plan-table td {
        font-size: 0.875rem; /* Reduced font size (14px) */
        padding: 0.6rem 0.5rem; /* Adjusted padding for smaller font */
        vertical-align: middle;
    }

    /* Specific styling for the BOM Qty input in the table */
    .usage-qty-input {
        font-weight: 500;
        text-align: right;
        background-color: #f1f3f5; /* A light grey for distinction */
        border: 1px solid transparent;
        border-radius: 0.25rem;
        padding: 0.375rem 0.5rem;
        transition: background-color .15s ease-in-out, border-color .15s ease-in-out;
        min-width: 120px;
    }

    .usage-qty-input:hover {
        background-color: #e9ecef;
    }

    .usage-qty-input:focus {
        background-color: #ffffff; /* White on focus for clear editing */
        border-color: #86b7fe;
    }

    /* Style for the update status message */
    .update-status {
        font-style: italic;
        font-size: 0.8rem;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('monitoring-plan.index') }}">Home</a></li>
                            <li class="breadcrumb-item active">Monitoring Plan</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Monitoring Plan</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('monitoring-plan.index') }}" method="GET" class="mb-3">
                            <div class="d-flex flex-wrap gap-2 search-form-container align-items-end">
                                <div class="flex-grow-1">
                                    <label for="vc_code_input" class="form-label">VC Code</label>
                                    <input type="text" id="vc_code_input" class="form-control" name="vc_code" value="{{ $vcCode ?? '' }}" placeholder="Search by VC Code...">
                                </div>
                                <div>
                                    <label for="plan-qty-input" class="form-label">Plan Qty</label>
                                    <input type="number" id="plan-qty-input" class="form-control" name="plan_qty" value="{{ $planQty ?? 1 }}" placeholder="Plan Qty...">
                                </div>
                                <div>
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                                <div>
                                    <a href="{{ route('monitoring-plan.index') }}" class="btn btn-secondary">Clear</a>
                                </div>
                                <div>
                                    <a href="{{ route('monitoring-plan.exportCsv', request()->query()) }}" class="btn btn-success">
                                        <i class="uil-file-export"></i> Export to CSV
                                    </a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="monitoring-plan-table">
                                <thead>
                                    <tr>
                                        <th>VC Code</th>
                                        <th>Option Code</th>
                                        <th>Model</th>
                                        <th>Color</th>
                                        <th>Material Number</th>
                                        <th>Material Name</th>
                                        <th>Unit</th>
                                        <th>BOM Qty / Unit</th>
                                        <th>Total Usage Qty</th>
                                        <th>WH Qty</th>
                                        <th>Line Side Qty</th>
                                        <th>CY Qty</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        @php
                                            $totalStock = ($item->WH_Qty ?? 0) + ($item->line_side_qty ?? 0) + ($item->cy_qty ?? 0);
                                            $totalUsage = $item->Usage_Qty * ($planQty ?? 1);
                                            $balance = $totalStock - $totalUsage;
                                        @endphp
                                        <tr>
                                            <td>{{ $item->vc_code }}</td>
                                            <td>{{ $item->option_code }}</td>
                                            <td>{{ $item->model }}</td>
                                            <td>{{ $item->color }}</td>
                                            <td>{{ $item->material_number }}</td>
                                            <td>{{ $item->material_name }}</td>
                                            <td>{{ $item->unit }}</td>
                                            <td>
                                                <input type="number" class="form-control usage-qty-input" value="{{ $item->Usage_Qty }}" data-id="{{ $item->bom_id }}" step="0.001">
                                                <span class="update-status small ms-1"></span>
                                            </td>
                                            <td class="total-usage-qty fw-bold">
                                                {{ number_format($totalUsage, 3) }}
                                            </td>
                                            <td class="wh-qty">{{ number_format($item->WH_Qty, 3) }}</td>
                                            <td class="line-side-qty">{{ number_format($item->line_side_qty, 3) }}</td>
                                            <td class="cy-qty">{{ number_format($item->cy_qty, 3) }}</td>
                                            <td class="balance-qty fw-bold">
                                                {{ number_format($balance, 3) }}
                                            </td>
                                            <td class="status-cell">
                                                @if ($balance >= 0)
                                                    <span class="badge bg-success status-badge">OK</span>
                                                @else
                                                    <span class="badge bg-danger status-badge">Not Enough</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="14" class="text-center">No data found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2">
                            {{ $data->links() }}
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Debounce function to limit how often a function can run.
    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), delay);
        };
    }

    // Function to update all total usage quantities
    function updateAllTotals() {
        let planQty = parseFloat($('#plan-qty-input').val()) || 0;
        $('#monitoring-plan-table tbody tr').each(function() {
            let row = $(this);
            let bomQtyInput = row.find('.usage-qty-input');
            if (bomQtyInput.length > 0) {
                let bomQty = parseFloat(bomQtyInput.val()) || 0;
                let totalUsage = bomQty * planQty;
                row.find('.total-usage-qty').text(totalUsage.toFixed(3).replace(/\.000$/, ''));
            }
        });
    }

    // Update totals when the main plan quantity input changes
    $('#plan-qty-input').on('input', debounce(updateAllTotals, 300));

    // Handle updates for individual BOM Qty inputs
    $('#monitoring-plan-table').on('input', '.usage-qty-input', function() {
        let input = $(this);
        // Update the total for the current row immediately
        let planQty = parseFloat($('#plan-qty-input').val()) || 0;
        let bomQty = parseFloat(input.val()) || 0;
        let totalUsage = bomQty * planQty;
        input.closest('tr').find('.total-usage-qty').text(totalUsage.toFixed(3).replace(/\.000$/, ''));
    });

    // AJAX call to save BOM Qty after user stops typing
    $('#monitoring-plan-table').on('input', '.usage-qty-input', debounce(function() {
        let input = $(this);
        let bomId = input.data('id');
        let newQty = input.val();
        let statusSpan = input.siblings('.update-status');

        if (bomId) {
            statusSpan.text('Updating...').css('color', 'orange').show();

            $.ajax({
                url: "{{ route('monitoring-plan.update') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    bom_id: bomId,
                    usage_qty: newQty
                },
                success: function(response) {
                    if (response.success) {
                        statusSpan.text('Saved!').css('color', 'green').delay(2000).fadeOut('slow', function() {
                            $(this).text('').show();
                        });
                    } else {
                        statusSpan.text('Error!').css('color', 'red');
                        console.error(response.message);
                    }
                },
                error: function(xhr) {
                    statusSpan.text('Failed!').css('color', 'red');
                    console.error(xhr.responseText);
                }
            });
        }
    }, 800)); // 800ms delay after user stops typing
});
</script>
@endpush