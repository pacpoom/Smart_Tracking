@extends('layouts.app')

@section('title', 'Open Container Tacking')

@section('content')
<div class="row">
    {{-- Constrain width on larger screens, full width on mobile --}}
    <div class="col-lg-6 col-md-8 mx-auto">
        <form action="{{ route('container-tacking.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Open Container Tacking</h5>
                    <button type="submit" class="btn btn-dark">Save Tacking</button>
                </div>
                <div class="card-body">
                    @include('layouts.partials.alerts')

                    {{-- Main Details --}}
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Container No.</label>
                            <select class="form-control" id="container-select" name="container_id" required></select>
                        </div>
                        <div class="col-12 mb-3">
                             <label class="form-label">Shipment / B/L</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="shipment">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Job Type</label>
                            <select class="form-control" name="job_type" required>
                                <option value="Inbound">Inbound</option>
                                <option value="Outbound">Outbound</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Container Type</label>
                            <select class="form-control" name="container_type" required>
                                <option value="CKD">CKD</option>
                                <option value="AIR">AIR</option>
                                <option value="LOCAL">LOCAL</option>
                                <option value="EXPORT">EXPORT</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Transport Type</label>
                            <select class="form-control" name="transport_type" required>
                                <option value="4W">4W</option>
                                <option value="6W">6W</option>
                                <option value="10W">10W</option>
                                <option value="20">20'</option>
                                <option value="40">40'</option>
                                <option value="40HQ">40' HQ</option>
                            </select>
                        </div>
                    </div>

                    <hr class="horizontal dark">

                    {{-- Photo Upload Section --}}
                    <h6 class="mt-4">Photo Upload</h6>
                    <div class="row">
                        @php
                            $photoTypes = [
                                'doc_receive_1' => 'รูปเอกสารรับตู้ 1', 'doc_receive_2' => 'รูปเอกสารรับตู้ 2',
                                'truck_front' => 'รูปหน้ารถ', 'truck_right' => 'รูปรถข้างขวา', 'truck_left' => 'รูปรถข้างซ้าย',
                                'wheel_ chock' => 'รูปหนุนหมอนรองล้อ', 'container_rear' => 'รูปท้ายตู้', 'seal' => 'รูปซีล',
                                'open_1' => 'รูปเปิดตู้ 1', 'open_2' => 'รูปเปิดตู้ 2', 'open_3' => 'รูปเปิดตู้ 3', 'open_4' => 'รูปเปิดตู้ 4', 'open_5' => 'รูปเปิดตู้ 5',
                                'open_6' => 'รูปเปิดตู้ 6', 'open_7' => 'รูปเปิดตู้ 7', 'open_8' => 'รูปเปิดตู้ 8', 'open_9' => 'รูปเปิดตู้ 9', 'open_10' => 'รูปเปิดตู้ 10',
                                'pallet_check_doc' => 'รูปเอกสารตรวจสอบ Pallet',
                                'empty_1' => 'รูปตู้เปล่า 1', 'empty_2' => 'รูปตู้เปล่า 2', 'empty_3' => 'รูปตู้เปล่า 3', 'empty_4' => 'รูปตู้เปล่า 4',
                                'empty_5' => 'รูปตู้เปล่า 5', 'empty_6' => 'รูปตู้เปล่า 6', 'empty_7' => 'รูปตู้เปล่า 7', 'empty_8' => 'รูปตู้เปล่า 8', 'empty_9' => 'รูปตู้เปล่า 9',
                                'condition_check_doc' => 'รูปเอกสารตรวจสอบสภาพตู้',
                            ];
                        @endphp

                        @foreach($photoTypes as $key => $label)
                        <div class="col-12 mb-3">
                            <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                            <div class="input-group input-group-outline">
                                <input class="form-control" type="file" name="photos[{{ $key }}]" id="{{ $key }}" accept="image/*">
                            </div>
                        </div>
                        @endforeach
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
        $('#container-select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Type to search for a container...',
            ajax: {
                url: '{{ route("containers.search") }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });
</script>
@endpush
