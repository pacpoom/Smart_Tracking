@extends('layouts.app')

@section('title', 'Exchange Details')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Exchange Details</h5>
        <a href="{{ route('container-exchange.index') }}" class="btn btn-outline-secondary">Back to List</a>
    </div>
    <div class="card-body">
        {{-- Main Details --}}
        <div class="row">
            {{-- แก้ไข: เปลี่ยนการเรียกใช้ความสัมพันธ์ --}}
            <div class="col-md-6 mb-3"><strong>Source Container (From):</strong> {{ $exchange->source_container_no ?? 'N/A' }}</div>
            <div class="col-md-6 mb-3"><strong>Destination Container (To):</strong> {{ $exchange->destination_container_no ?? 'N/A' }}</div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6 mb-3"><strong>Exchanged By:</strong> {{ $containerExchange->user->name }}</div>
            <div class="col-md-6 mb-3"><strong>Date:</strong> {{ $containerExchange->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <strong>Remarks:</strong> {{ $containerExchange->remarks ?? 'N/A' }}
            </div>
        </div>
        
        <hr class="horizontal dark">

        {{-- Photo Gallery --}}
        <h6 class="mt-4">Uploaded Photos</h6>
        @php
            // Define photo types and their descriptions
            $photoDescriptions = [
                1 => 'ถ่ายรูปเบอร์ตู้เก่า',
                2 => 'ถ่ายรูปซีลตู้คอนเทนเนอร์ก่อนเปิด',
                3 => 'ถ่ายรูปเบอร์ตู้ใหม่',
                4 => 'ถ่ายรูปหลังเปิดตู้เก่า (เช็คของในตู้ มีความเสียหายไหม)',
                5 => 'ถ่ายรูปหลังเปิดตู้ใหม่ (เช็คสถานะของตู้เปล่า มีความเสียหายไหม)',
                6 => 'ถ่ายรูปของในตู้ใหม่ (เช็คของในตู้หลังจากย้ายของเสร็จ มีความเสียหายไหม)',
                7 => 'ถ่ายรูปซีลตู้คอนเทนเนอร์ (หลังจากปิดตู้ใหม่)',
            ];
            // Group photos by their type
            $groupedPhotos = $containerExchange->photos->keyBy('photo_type');
        @endphp

        <div class="row">
            @forelse($groupedPhotos as $photoType => $photo)
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                        {{-- แก้ไขการเรียกใช้รูปภาพให้เหมือนกับ container-tacking --}}
                        <a href="{{ route('container-exchange.showPhoto', $photo->id) }}" target="_blank">
                            <img src="{{ route('container-exchange.showPhoto', $photo->id) }}" class="img-fluid border-radius-lg" style="height: 200px; width: 100%; object-fit: cover;">
                        </a>
                    </div>
                    <div class="card-body pt-2">
                        <span class="text-xs">{{ $photoDescriptions[$photo->photo_type] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p>No photos were uploaded for this exchange record.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
