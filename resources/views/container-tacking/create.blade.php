@extends('layouts.app')

@section('title', 'Open Container Tacking')

@section('content')
<div class="row">
    <div class="col-lg-6 col-md-8 mx-auto">
        <form action="{{ route('container-tacking.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Open Container Tacking</h5>
                </div>
                <div class="card-body">
                    @include('layouts.partials.alerts')

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Container Plan</label>
                            <select class="form-control" id="plan-select" name="container_order_plan_id" required></select>
                            @error('container_order_plan_id') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        </div>
                        <div class="col-12 mb-3">
                             <label class="form-label">Shipment / B/L</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="shipment" id="shipment-input" disabled="true">
                            </div>
                            @error('shipment') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Job Type</label>
                            <select class="form-control" name="job_type" required>
                                <option value="Inbound">Inbound</option>
                                <option value="Outbound">Outbound</option>
                            </select>
                            @error('job_type') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Container Type</label>
                            <select class="form-control" name="container_type" required>
                                <option value="CKD">CKD</option>
                                <option value="AIR">AIR</option>
                                <option value="LOCAL">LOCAL</option>
                                <option value="EXPORT">EXPORT</option>
                            </select>
                            @error('container_type') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
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
                            @error('transport_type') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        </div>
                    </div>

                    <hr class="horizontal dark">

                    {{-- Photo Upload Section with Pagination --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h6 class="mb-0">Photo Upload</h6>
                        <span id="step-indicator" class="badge bg-gradient-secondary">Step 1 of 3</span>
                    </div>
                    @error('photos') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror

                    @php
                        $photoGroups = [
                            1 => [
                                'doc_receive_1' => 'รูปเอกสารรับตู้ 1', 'doc_receive_2' => 'รูปเอกสารรับตู้ 2',
                                'truck_front' => 'รูปหน้ารถ', 'truck_right' => 'รูปรถข้างขวา', 'truck_left' => 'รูปรถข้างซ้าย',
                                'wheel_chock' => 'รูปหนุนหมอนรองล้อ', 'container_rear' => 'รูปท้ายตู้', 'seal' => 'รูปซีล',
                                'pallet_check_doc' => 'รูปเอกสารตรวจสอบ Pallet',
                            ],
                            2 => [
                                'open_1' => 'รูปเปิดตู้ 1', 'open_2' => 'รูปเปิดตู้ 2', 'open_3' => 'รูปเปิดตู้ 3', 'open_4' => 'รูปเปิดตู้ 4', 'open_5' => 'รูปเปิดตู้ 5',
                                'open_6' => 'รูปเปิดตู้ 6', 'open_7' => 'รูปเปิดตู้ 7', 'open_8' => 'รูปเปิดตู้ 8', 'open_9' => 'รูปเปิดตู้ 9', 'open_10' => 'รูปเปิดตู้ 10',
                            ],
                            3 => [
                                'empty_1' => 'รูปตู้เปล่า 1', 'empty_2' => 'รูปตู้เปล่า 2', 'empty_3' => 'รูปตู้เปล่า 3', 'empty_4' => 'รูปตู้เปล่า 4',
                                'empty_5' => 'รูปตู้เปล่า 5', 'empty_6' => 'รูปตู้เปล่า 6', 'empty_7' => 'รูปตู้เปล่า 7', 'empty_8' => 'รูปตู้เปล่า 8', 'empty_9' => 'รูปตู้เปล่า 9',
                                'condition_check_doc' => 'รูปเอกสารตรวจสอบสภาพตู้',
                            ]
                        ];
                    @endphp
                    
                    {{-- Steps for photo upload --}}
                    @foreach($photoGroups as $step => $photos)
                    <div id="step-{{ $step }}" class="photo-step" style="{{ $step > 1 ? 'display: none;' : '' }}">
                        <div class="row mt-3">
                            @foreach($photos as $key => $label)
                                <div class="col-12 mb-3">
                                    <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                                    <div class="input-group input-group-outline">
                                        <input class="form-control photo-input" type="file" name="photos[{{ $key }}]" id="{{ $key }}" accept="image/*" data-preview-id="{{ $key }}-preview">
                                    </div>
                                    @error('photos.'.$key) <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                    <div class="mt-2 text-center">
                                        <img id="{{ $key }}-preview" src="#" alt="Image Preview" class="img-fluid border-radius-lg" style="display: none; max-height: 150px;"/>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    {{-- Navigation Buttons --}}
                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" id="prev-btn" style="display: none;">Previous</button>
                        <button type="button" class="btn btn-dark" id="next-btn">Next</button>
                        <button type="submit" class="btn btn-dark" id="save-btn" style="display: none;">Save Tacking</button>
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
        $('#plan-select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Search by Plan No, Container No, or B/L...',
            ajax: {
                url: '{{ route("container-order-plans.search") }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.text,
                                house_bl: item.house_bl // Pass the house_bl data
                            }
                        })
                    };
                },
                cache: true
            }
        });

        // Autofill Shipment / B/L
        $('#plan-select').on('select2:select', function (e) {
            var data = e.params.data;
            if (data.house_bl) {
                const shipmentInput = $('#shipment-input');
                shipmentInput.val(data.house_bl);
                // Trigger Material Dashboard's is-filled class
                shipmentInput.parent().addClass('is-filled');
            }
        });

        // Step navigation logic
        let currentStep = 1;
        const totalSteps = 3;
        const nextBtn = $('#next-btn');
        const prevBtn = $('#prev-btn');
        const saveBtn = $('#save-btn');
        const stepIndicator = $('#step-indicator');

        function showStep(step) {
            $('.photo-step').hide();
            $('#step-' + step).show();
            stepIndicator.text('Step ' + step + ' of ' + totalSteps);

            prevBtn.toggle(step > 1);
            nextBtn.toggle(step < totalSteps);
            saveBtn.toggle(step === totalSteps);
        }

        nextBtn.on('click', function() {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        });

        prevBtn.on('click', function() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        // Confirmation before changing a photo
        $('.photo-input').on('click', function(e) {
            const previewId = $(this).data('preview-id');
            const previewImage = $('#' + previewId);
            if (previewImage.is(':visible')) {
                if (!confirm('A photo is already selected. Do you want to choose a new one?')) {
                    e.preventDefault();
                }
            }
        });

        // Image preview logic
        $('.photo-input').on('change', function() {
            const previewId = $(this).data('preview-id');
            const previewImage = $('#' + previewId);

            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.attr('src', e.target.result).show();
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                previewImage.hide().attr('src', '#');
            }
        });
    });
</script>
@endpush
