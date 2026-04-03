@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-undo me-2"></i> Container Return</h5>
                </div>

                <div class="card-body bg-light">
                    
                    <!-- Search Bar (Text Input) -->
                    <form action="{{ route('container-return.index') }}" method="GET" class="mb-4">
                        <div class="row g-2">
                            <!-- Input Search -->
                            <div class="col-12 col-md-8 col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-box text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" name="search" placeholder="-- Search Container No --" value="{{ request('search') }}" autofocus autocomplete="off">
                                </div>
                            </div>
                            <!-- Buttons -->
                            <div class="col-6 col-md-2 col-lg-auto flex-grow-1">
                                <button class="btn btn-primary w-100 text-nowrap fw-bold" type="submit">
                                    <i class="fas fa-search me-1"></i> Search
                                </button>
                            </div>
                            <div class="col-6 col-md-2 col-lg-auto flex-grow-1">
                                <a href="{{ route('container-return.index') }}" class="btn btn-secondary w-100 text-nowrap fw-bold">
                                    <i class="fas fa-sync-alt me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Feedback Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if($errors->has('general'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i> {{ $errors->first('general') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- List of Containers (Card Layout) -->
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @forelse($stocks as $stock)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                                    @php
                                        // Handle cases where container relation might be direct or via order plan
                                        $containerNo = $stock->container->container_no ?? ($stock->containerOrderPlan->container->container_no ?? 'N/A');
                                    @endphp
                                    <h5 class="card-title text-primary fw-bold">
                                        {{ $containerNo }}
                                    </h5>
                                </div>
                                <div class="card-body py-2">
                                    <div class="mb-2">
                                        <small class="text-muted d-block">Current Location</small>
                                        <span class="badge bg-secondary">
                                            {{ $stock->yardLocation->location_code ?? 'Unknown Location' }}
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted d-block">Container Status</small>
                                        @if($stock->status == 1)
                                            <span class="badge bg-danger">Full</span>
                                        @elseif($stock->status == 2)
                                            <span class="badge bg-warning text-dark">Partial</span>
                                        @elseif($stock->status == 3)
                                            <span class="badge bg-success">Empty</span>
                                        @else
                                            <span class="badge bg-secondary">Unknown</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0 pb-3 pt-0">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-warning" onclick="openReturnModal({{ $stock->container_id }}, '{{ $containerNo }}')">
                                            <i class="fas fa-undo"></i> Return
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5 w-100">
                            <div class="text-muted">
                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                <h5>No containers available for return</h5>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $stocks->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Modal -->
@include('container-return.partials.return-modal')

@endsection

@section('scripts')
<script>
    // สามารถเพิ่ม Script อื่นๆ ที่จำเป็นในหน้านี้ได้
    // นำ Select2 script ออกแล้วเพื่อใช้ Textbox ค้นหาแบบปกติ
</script>
@endsection