@extends('layouts.app')

@section('title', 'Tacking Details')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Tacking Details for: {{ $containerTacking->containerOrderPlan?->container?->container_no ?? 'N/A' }}</h5>
        <div>
            @can('add damage photos')
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addPhotosModal">
                    Report Problem / Add Photos
                </button>
            @endcan
            <a href="{{ route('container-tacking.index') }}" class="btn btn-outline-secondary">Back to List</a>
        </div>
    </div>
    <div class="card-body">
        {{-- Main Details --}}
        <div class="row">
            <div class="col-md-3"><strong>Container No:</strong> {{ $containerTacking->containerOrderPlan?->container?->container_no ?? 'N/A' }}</div>
            <div class="col-md-3"><strong>Shipment/B/L:</strong> {{ $containerTacking->shipment }}</div>
            <div class="col-md-3"><strong>Job Type:</strong> {{ $containerTacking->job_type }}</div>
            <div class="col-md-3"><strong>Container Type:</strong> {{ $containerTacking->container_type }}</div>
        </div>
        <div class="row mt-2">
            <div class="col-md-3"><strong>Transport Type:</strong> {{ $containerTacking->transport_type }}</div>
            <div class="col-md-3"><strong>Operator:</strong> {{ $containerTacking->user->name }}</div>
            <div class="col-md-3"><strong>Date:</strong> {{ $containerTacking->created_at->format('d/m/Y H:i') }}</div>
        </div>
        
        <hr class="horizontal dark">

        {{-- Photo Gallery --}}
        <h6 class="mt-4">Uploaded Photos</h6>
        @php
            // Group photos by original types and then by damage batches
            $initialPhotos = $containerTacking->photos->whereNull('batch_key');
            $damageBatches = $containerTacking->photos->whereNotNull('batch_key')->groupBy('batch_key');
        @endphp

        {{-- Initial Photos --}}
        <div class="row">
            @forelse($initialPhotos as $photo)
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                        <a href="{{ route('container-tacking.photo.show', $photo->id) }}" target="_blank">
                            <img src="{{ route('container-tacking.photo.show', $photo->id) }}" class="img-fluid border-radius-lg" style="height: 200px; width: 100%; object-fit: cover;">
                        </a>
                    </div>
                    <div class="card-body pt-2">
                        <span class="text-xs">{{ str_replace('_', ' ', Str::title($photo->photo_type)) }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p>No initial photos were uploaded for this tacking record.</p>
            </div>
            @endforelse
        </div>

        {{-- Damage Photos --}}
        @if($damageBatches->isNotEmpty())
            <hr class="horizontal dark mt-4">
            <h6 class="mt-4">Damage / Problem Photos</h6>
            @foreach($damageBatches as $batchKey => $photos)
                <div class="mb-4">
                    <p><strong>Remarks:</strong> {{ $photos->first()->remarks ?: 'No remarks for this batch.' }}</p>
                    <div class="row">
                        @foreach($photos as $photo)
                        <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                            <div class="card">
                                <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                    <a href="{{ route('container-tacking.photo.show', $photo->id) }}" target="_blank">
                                        <img src="{{ route('container-tacking.photo.show', $photo->id) }}" class="img-fluid border-radius-lg" style="height: 200px; width: 100%; object-fit: cover;">
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

{{-- Modal for Adding Damage Photos --}}
<div class="modal fade" id="addPhotosModal" tabindex="-1" aria-labelledby="addPhotosModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('container-tacking.addPhotos', $containerTacking->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addPhotosModalLabel">Report Problem / Add Photos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Photo 1 (Max 3)</label>
                        <div class="input-group input-group-outline">
                            <input class="form-control" type="file" name="damage_photos[]" accept="image/*" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Photo 2 (Optional)</label>
                        <div class="input-group input-group-outline">
                            <input class="form-control" type="file" name="damage_photos[]" accept="image/*">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Photo 3 (Optional)</label>
                        <div class="input-group input-group-outline">
                            <input class="form-control" type="file" name="damage_photos[]" accept="image/*">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <div class="input-group input-group-outline">
                            <textarea class="form-control" name="remarks" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Upload Photos</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
