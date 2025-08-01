@extends('layouts.app')

@section('title', 'Exchange Container')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <form action="{{ route('container-exchange.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Exchange Container</h5>
                    <button type="submit" class="btn btn-dark">Confirm Exchange</button>
                </div>
                <div class="card-body">
                    @include('layouts.partials.alerts')

                    {{-- เพิ่มส่วนแสดงข้อความ Error ทั่วไป --}}
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
                            {{-- เพิ่มส่วนแสดงข้อความ Error เฉพาะฟิลด์ --}}
                            @error('source_container_stock_id') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Destination Container (To)</label>
                            <select class="form-control stock-select" id="destination-select" name="destination_container_stock_id" required></select>
                             {{-- เพิ่มส่วนแสดงข้อความ Error เฉพาะฟิลด์ --}}
                            @error('destination_container_stock_id') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <div class="input-group input-group-outline">
                            <textarea class="form-control" name="remarks" rows="3"></textarea>
                        </div>
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
            placeholder: 'Search by Container No. or Plan No...',
            ajax: {
                url: '{{ route("container-stocks.search") }}',
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
