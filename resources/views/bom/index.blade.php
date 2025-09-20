@extends('layouts.app')

@section('title', 'Bill of Material (BOM)')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
                <h5 class="mb-3 mb-md-0">Bill of Material (BOM)</h5>
                <form action="{{ route('bom.index') }}" method="GET" class="w-100 w-md-auto">
                    <div class="input-group input-group-outline {{ request('search') ? 'is-filled' : '' }}">
                        <label class="form-label">Search by VC Code...</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">VC
                                Code</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Option Code</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Model</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Material Number</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Material Name</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($boms as $bom)
                            <tr>
                                <td class="align-middle text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $bom->vcMaster->vc_code ?? 'N/A' }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $bom->vcMaster->option_code ?? 'N/A' }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $bom->vcMaster->model ?? 'N/A' }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{ $bom->childMaterial->material_number ?? 'N/A' }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{ $bom->childMaterial->material_name ?? 'N/A' }}</p>
                                </td>
                                <td class="align-middle text-center"><span
                                        class="text-secondary text-xs font-weight-bold">{{ $bom->childMaterial->unit ?? 'N/A' }}</span>
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
        <div class="card-footer d-flex align-items-center justify-content-between">
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
        </div>
    </div>
@endsection
