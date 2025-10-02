@extends('layouts.app')

@section('title', 'Bill of Material (BOM)')

@section('content')
    {{-- แสดงข้อความแจ้งเตือน Success/Error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
            <span class="alert-icon"><i class="ni ni-like-2"></i></span>
            <span class="alert-text"><strong>Success!</strong> {{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
            <span class="alert-icon"><i class="ni ni-bell-55"></i></span>
            <span class="alert-text"><strong>Error!</strong> {{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
                <h5 class="mb-3 mb-md-0">Bill of Material (BOM)</h5>
                <div class="d-flex mt-3 mt-md-0">
                    <form action="{{ route('bom.index') }}" method="GET" class="w-100 w-md-auto me-2">
                        <div class="input-group input-group-outline {{ request('search') ? 'is-filled' : '' }}">
                            <label class="form-label">Search by VC Code...</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                        </div>
                    </form>
                    <button type="button" class="btn btn-success mb-0" data-bs-toggle="modal" data-bs-target="#importModal">
                        Import
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">VC Code</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Option Code</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material Number</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material Name</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($boms as $bom)
                            <tr>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $bom->vcMaster->vc_code ?? 'N/A' }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $bom->vcMaster->option_code ?? 'N/A' }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $bom->childMaterial->material_number ?? 'N/A' }}</span>
                                </td>
                                <td class="align-middle">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $bom->childMaterial->material_name ?? 'N/A' }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $bom->childMaterial->unit ?? 'N/A' }}</span>
                                </td>
                                <td class="align-middle text-center"><span
                                        class="text-secondary text-xs font-weight-bold">{{ $bom->qty }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center p-3">No Bill of Material data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- <div class="card-footer d-flex align-items-center justify-content-between">
            <form action="{{ route('bom.index') }}" method="GET" class="w-auto">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <select name="per_page" class="form-select ps-2" onchange="this.form.submit()">
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 page</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 page</option>
                    <option value="75" {{ $perPage == 75 ? 'selected' : '' }}>75 page</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 page</option>
                </select>
            </form>
            {{ $boms->withQueryString()->links() }}
        </div> --}}
    </div>

    <!-- Modal สำหรับ Import -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import BOM Master File</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('bom.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                         <p class="small">โปรดตรวจสอบว่าไฟล์ Excel ของคุณมีคอลัมน์ต่อไปนี้: <strong>vc_code</strong>, <strong>material_number</strong>, <strong>qty</strong></p>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('bom.template') }}" class="btn btn-link text-success p-0 mb-2">
                                <i class="fas fa-file-excel me-1"></i> Download Template
                            </a>
                        </div>
                        <div class="input-group input-group-outline my-3">
                             <input class="form-control" type="file" id="file" name="file" accept=".xlsx, .xls" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-gradient-primary">Upload & Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection