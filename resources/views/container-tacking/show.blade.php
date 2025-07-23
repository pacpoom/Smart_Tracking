@extends('layouts.app')

@section('title', 'Tacking Details')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Tacking Details for: {{ $containerTacking->container->container_no }}</h5>
        <a href="{{ route('container-tacking.index') }}" class="btn btn-outline-secondary">Back to List</a>
    </div>
    <div class="card-body">
        {{-- Main Details --}}
        <div class="row">
            <div class="col-md-3"><strong>Container No:</strong> {{ $containerTacking->container->container_no }}</div>
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
        <div class="row">
            @forelse($containerTacking->photos as $photo)
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                        <a href="{{ route('container-tacking.photo.show', $photo->id) }}" target="_blank">
                            {{-- แก้ไข: เพิ่ม style เพื่อกำหนดขนาดรูปภาพ --}}
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
                <p>No photos were uploaded for this tacking record.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
