@extends('layouts.app')

@section('title', 'Change Container Location')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Change Container Location</h5>
            {{-- 1. เปลี่ยนจากช่อง input ธรรมดาเป็น select2 --}}
            <div class="input-group input-group-outline w-100 w-md-auto">
                <label class="form-label">Search by Container No...</label>
                <select id="container-search" class="form-control" style="width: 100%;"></select>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('layouts.partials.alerts')

        <div class="row">
            @forelse ($containers as $stock)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border">
                        <div class="card-header border-bottom pb-2">
                            <h6 class="mb-0">{{ $stock->containerOrderPlan?->container?->container_no ?? 'N/A' }}</h6>
                            <p class="text-sm mb-0">Size: {{ $stock->containerOrderPlan?->container?->size ?? 'N/A' }}</p>
                            <p class="text-sm mb-0">Agent: {{ $stock->containerOrderPlan?->container?->agent ?? 'N/A' }}</p>
                            <p class="text-sm mb-0">Depot: {{ $stock->containerOrderPlan?->depot ?? 'N/A' }}</p>
                            <p class="text-sm mb-0">Age in yard: {{ $stock->containerOrderPlan?->age_in_days ?? 'N/A' }} days</p>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Current Location:</strong></p>
                            <h5 class="font-weight-bolder">{{ $stock->yardLocation?->location_code ?? 'N/A' }}</h5>
                        </div>
                        <div class="card-footer pt-0">
                            <button type="button" class="btn btn-dark w-100 mb-0" data-bs-toggle="modal" data-bs-target="#changeLocationModal-{{ $stock->id }}">
                                Change Location
                            </button>
                        </div>
                    </div>
                </div>
                @include('container-change-location.partials.change-location-modal', ['stock' => $stock])
            @empty
                <div class="col-12">
                    <p class="text-center p-3">No containers in stock to move.</p>
                </div>
            @endforelse
        </div>
    </div>
    <div class="card-footer d-flex justify-content-center">
        {{-- Pagination --}}
        {{ $containers->withQueryString()->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 2. Initialise the main search bar with Select2 for AJAX
    $('#container-search').select2({
        theme: 'bootstrap-5',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: 'Search for a container...',
        allowClear: true,
        ajax: {
            url: '/container-stocks/search',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    term: params.term // search term
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    }).on('select2:select', function(e) {
        // 3. เมื่อเลือก container จาก dropdown ให้เปลี่ยน URL เพื่อทำการค้นหา
        let stockId = e.params.data.id;
        // ส่งค่า 'search' ไปที่ URL เพื่อให้ Controller ทำการค้นหา
        window.location.href = '{{ route("container-change-location.index") }}?search=' + encodeURIComponent(e.params.data.text.split(' ')[0]);

    }).on('select2:unselect', function(e) {
        // เมื่อยกเลิกการเลือก ให้รีโหลดหน้าเดิมเพื่อแสดงข้อมูลทั้งหมด
        window.location.href = '{{ route("container-change-location.index") }}';
    });

    // 4. Existing script for the location dropdown inside the modal
    document.body.addEventListener('show.bs.modal', function(event) {
        let modal = event.relatedTarget ? document.getElementById(event.relatedTarget.getAttribute('data-bs-target').substring(1)) : event.target;
        if (modal.id.startsWith('changeLocationModal-')) {
            let selectElement = modal.querySelector('.location-select');
            if (selectElement && !$(selectElement).data('select2')) {
                let current_location_id = $(selectElement).data('current-location-id');
                $(selectElement).select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $(modal),
                    placeholder: 'Type to search for a location...',
                    ajax: {
                        //url: '{{ route("yard-locations.search") }}',
                        url: '/yard-locations/search',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term,
                                exclude: current_location_id
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });
            }
        }
    });
});
</script>
@endpush
