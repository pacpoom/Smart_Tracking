@extends('layouts.app')

@section('title', 'Edit Container')

@section('content')
{{-- 1. เพิ่ม Row และ Column เพื่อจำกัดความกว้างของฟอร์ม --}}
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <form action="{{ route('containers.update', $container->id) }}" method="POST">
                @method('PUT')
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Edit Container: {{ $container->container_no }}</h5>
                    <div>
                        <a href="{{ route('containers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-dark">Update Container</button>
                    </div>
                </div>
                <div class="card-body">
                    @include('containers.partials._form', ['container' => $container])
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
