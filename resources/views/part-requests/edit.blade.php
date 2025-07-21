@extends('layouts.app')

@section('title', 'Update Part Request')

@section('content')
{{-- 1. กำหนดตัวแปรสำหรับล็อกฟอร์ม --}}
@php
    $isLocked = $partRequest->status === 'delivery' && !auth()->user()->can('super_admin');
@endphp

<div class="card">
    <div class="card-header">
        <h5>Update Request: #{{ $partRequest->id }} for {{ $partRequest->part->part_number }}</h5>
    </div>
    <div class="card-body">
        {{-- 2. แสดงข้อความแจ้งเตือนถ้าฟอร์มถูกล็อก --}}
        @if($isLocked)
            <div class="alert alert-info text-white" role="alert">
                This request has been processed and is now locked. Only a Super Admin can make further changes.
            </div>
        @endif

        <form action="{{ route('part-requests.update', $partRequest->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <strong>Part:</strong> {{ $partRequest->part->part_number }}
                </div>
                <div class="col-md-4">
                    <strong>Requester:</strong> {{ $partRequest->user->name }}
                </div>
                 <div class="col-md-4">
                    <strong>Quantity:</strong> {{ $partRequest->quantity }}
                </div>
            </div>

            <hr class="horizontal dark">

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <div class="input-group input-group-outline">
                        {{-- 3. เพิ่ม attribute 'disabled' --}}
                        <select class="form-control" name="status" required {{ $isLocked ? 'disabled' : '' }}>
                            <option value="pending" {{ $partRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $partRequest->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $partRequest->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="delivery" {{ $partRequest->status == 'delivery' ? 'selected' : '' }}>Delivery</option>
                        </select>
                    </div>
                    @error('status') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Delivery Date</label>
                    <div class="input-group input-group-outline">
                        <input type="date" class="form-control" name="delivery_date" value="{{ old('delivery_date', $partRequest->delivery_date?->format('Y-m-d')) }}" {{ $isLocked ? 'disabled' : '' }}>
                    </div>
                    @error('delivery_date') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Arrival Date</label>
                    <div class="input-group input-group-outline">
                        <input type="date" class="form-control" name="arrival_date" value="{{ old('arrival_date', $partRequest->arrival_date?->format('Y-m-d')) }}" {{ $isLocked ? 'disabled' : '' }}>
                    </div>
                    @error('arrival_date') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                </div>
            </div>
             <div class="mb-3">
                <label class="form-label">Delivery Document</label>
                <div class="input-group input-group-outline">
                    <input class="form-control" type="file" name="delivery_document" {{ $isLocked ? 'disabled' : '' }}>
                </div>
                @error('delivery_document') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                @if($partRequest->delivery_document_path)
                    <p class="text-sm mt-2">Current file: <a href="{{ route('part-requests.downloadDeliveryDocument', $partRequest->id) }}">{{ basename($partRequest->delivery_document_path) }}</a></p>
                @endif
            </div>

            <div class="mt-4">
                {{-- 4. ซ่อนปุ่มถ้าฟอร์มถูกล็อก --}}
                @unless($isLocked)
                    <button type="submit" class="btn btn-dark">Update Request</button>
                @endunless
                <a href="{{ route('part-requests.index') }}" class="btn btn-outline-secondary">Back to List</a>
            </div>
        </form>
    </div>
</div>
@endsection
