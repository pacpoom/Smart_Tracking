@extends('layouts.app')

@section('title', 'Exchange Container')

@push('styles')
<style>
    /* General styles for photo previews */
    .photo-preview {
        max-width: 100%;
        max-height: 150px;
        width: auto;
        height: auto;
        display: none;
        object-fit: contain; /* Ensures the entire image is visible without distortion */
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <form action="{{ route('container-exchange.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="mb-2 mb-md-0">Exchange Container</h5>
                    <button type="submit" class="btn btn-dark" id="save-btn" style="display: none;">Confirm Exchange</button>
                </div>
                <div class="card-body">
                    @include('layouts.partials.alerts')

                    @if ($errors->any())
                        <div class="alert alert-danger text-white">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Source Container (From)</label>
                            <select class="form-control stock-select" id="source-select" name="source_container_stock_id" required></select>
                            @error('source_container_stock_id') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Destination Container (To)</label>
                            <select class="form-control stock-select" id="destination-select" name="destination_container_stock_id" required></select>
                            @error('destination_container_stock_id') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <div class="input-group input-group-outline">
                            <textarea class="form-control" name="remarks" rows="3"></textarea>
                        </div>
                    </div>

                    <hr class="horizontal dark">

                    {{-- Photo Upload Section with Pagination --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h6 class="mb-0">Photo Upload</h6>
                        <span id="step-indicator" class="badge bg-gradient-secondary">Step 1 of 2</span>
                    </div>

                    @php
                        $photoGroups = [
                            1 => [
                                'photos_1' => 'ถ่ายรูปเบอร์ตู้เก่า',
                                'photos_2' => 'ถ่ายรูปซีลตู้คอนเทนเนอร์ก่อนเปิด',
                                'photos_3' => 'ถ่ายรูปเบอร์ตู้ใหม่',
                                'photos_4' => 'ถ่ายรูปหลังเปิดตู้เก่า (เช็คของในตู้ มีความเสียหายไหม)',
                            ],
                            2 => [
                                'photos_5' => 'ถ่ายรูปหลังเปิดตู้ใหม่ (เช็คสถานะของตู้เปล่า มีความเสียหายไหม)',
                                'photos_6' => 'ถ่ายรูปของในตู้ใหม่ (เช็คของในตู้หลังจากย้ายของเสร็จ มีความเสียหายไหม)',
                                'photos_7' => 'ถ่ายรูปซีลตู้คอนเทนเนอร์ (หลังจากปิดตู้ใหม่)',
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
                                        <input class="form-control photo-input" type="file" name="photos[{{ str_replace('photos_', '', $key) }}]" id="{{ $key }}" accept="image/*" data-preview-id="{{ $key }}-preview">
                                    </div>
                                    @error('photos.' . str_replace('photos_', '', $key)) <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                    <div class="mt-2 text-center">
                                        <img id="{{ $key }}-preview" src="#" alt="Image Preview" class="img-fluid border-radius-lg photo-preview"/>
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
        $('.stock-select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Search by',
            ajax: {
                url: '/container-stocks/search',
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

        // Step navigation logic
        let currentStep = 1;
        const totalSteps = 2;
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
        
        // Initial state
        showStep(currentStep);
    });
</script>
@endpush