@extends('layouts.app')

@section('title', 'Request Part')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <form id="part-request-form" action="{{ route('part-requests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Part Request Form</h5>
                    <div>
                        <a href="{{ route('part-requests.index') }}" class="btn btn-outline-secondary">Back to List</a>
                        <button type="submit" class="btn btn-dark">Submit Request</button>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success text-white">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div id="stock-error" class="alert alert-danger text-white" style="display: none;"></div>

                    <div class="mb-3">
                        <label class="form-label">Requester</label>
                        <div class="input-group input-group-outline">
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly disabled>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Select Part</label>
                        <select class="form-control" id="part-select" name="part_id" required></select>
                        <p id="stock-display" class="text-sm text-muted mt-1" style="display: none;">
                            Available Stock: <span id="stock-qty" class="font-weight-bold"></span>
                        </p>
                        @error('part_id') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantity</label>
                            <div class="input-group input-group-outline">
                                <input type="number" class="form-control" name="quantity" value="{{ old('quantity') }}" min="1" required>
                            </div>
                             @error('quantity') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Required Date</label>
                            <div class="input-group input-group-outline">
                                <input type="date" class="form-control" name="required_date" value="{{ old('required_date') }}" required>
                            </div>
                             @error('required_date') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason for Request</label>
                        <div class="input-group input-group-outline">
                            <textarea class="form-control" name="reason" rows="3">{{ old('reason') }}</textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dealer Name</label>
                        <div class="input-group input-group-outline">
                            <input type="text" class="form-control" name="foc_no" value="{{ old('foc_no') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label">Attachment (Optional)</label>
                        <div class="input-group input-group-outline">
                            <input class="form-control" type="file" name="attachment">
                        </div>
                        @error('attachment') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let selectedStock = -1; // Variable to store stock of selected part

        $('#part-select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Type to search for a part...',
            ajax: {
                //url: '{{ route("parts.search") }}',
                url: '/parts/search',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.text,
                                stock: item.stock
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('#part-select').on('select2:select', function (e) {
            var data = e.params.data;
            if (data.stock !== undefined) {
                selectedStock = data.stock;
                $('#stock-qty').text(data.stock);
                $('#stock-display').show();
                $('#stock-error').hide();
            } else {
                selectedStock = -1;
                $('#stock-display').hide();
            }
        });

        $('#part-request-form').on('submit', function(e) {
            const requestedQty = parseInt($('input[name="quantity"]').val(), 10);
            const errorDiv = $('#stock-error');

            if (selectedStock <= 0) {
                e.preventDefault();
                errorDiv.text('This part is out of stock and cannot be requested.');
                errorDiv.show();
                return;
            }

            if (requestedQty > selectedStock) {
                e.preventDefault();
                errorDiv.text('Requested quantity (' + requestedQty + ') exceeds available stock (' + selectedStock + ').');
                errorDiv.show();
            } else {
                errorDiv.hide();
            }
        });
    });
</script>
@endpush
