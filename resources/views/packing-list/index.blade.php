@extends('layouts.app')

@section('title', 'Packing List')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
            <h5 class="mb-3 mb-md-0">Packing List</h5>
        </div>
    </div>

    <div class="card-body">
        {{-- Search & Action Form --}}
        <form action="{{ route('packing-list.index') }}" method="GET" class="mb-4" id="searchForm">
            <div class="row g-3 align-items-center">
                {{-- Inputs --}}
                <div class="col-md-2">
                    <div class="input-group input-group-outline my-3 {{ request('delivery_order') ? 'is-filled' : '' }}">
                        <label class="form-label">Delivery Order</label>
                        <input type="text" name="delivery_order" class="form-control" value="{{ request('delivery_order') }}">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="input-group input-group-outline my-3 {{ request('container') ? 'is-filled' : '' }}">
                        <label class="form-label">Container</label>
                        <input type="text" name="container" class="form-control" value="{{ request('container') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="input-group input-group-outline my-3 {{ request('box_id') ? 'is-filled' : '' }}">
                        <label class="form-label">Box ID</label>
                        <input type="text" name="box_id" class="form-control" value="{{ request('box_id') }}">
                    </div>
                </div>

                {{-- Per Page Selector --}}
                <div class="col-md-2">
                    <div class="input-group input-group-outline my-3 is-filled">
                        <label class="form-label">Show / Page</label>
                        <select name="per_page" class="form-control" onchange="document.getElementById('searchForm').submit()">
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            <option value="500" {{ $perPage == 500 ? 'selected' : '' }}>500</option>
                            <option value="2000" {{ $perPage == 2000 ? 'selected' : '' }}>2000</option>
                        </select>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="col-md-4 d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary mb-0">
                        Search
                    </button>
                    <a href="{{ route('packing-list.index') }}" class="btn btn-outline-secondary mb-0">
                        Reset
                    </a>
                    {{-- ปุ่ม Export ใช้ formaction เพื่อส่ง request ไปยัง route export โดยใช้ข้อมูลใน form เดียวกัน --}}
                    <button type="submit" formaction="{{ route('packing-list.export') }}" class="btn btn-success mb-0">
                        Export CSV
                    </button>
                </div>
            </div>
        </form>

        @include('layouts.partials.alerts')
        
        <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Storage Location</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Delivery Order</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Receive Status</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Container</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Case Number</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Box ID</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Temp Material</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-end pe-4">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($packingLists as $item)
                        <tr>
                            <td class="ps-3">
                                <p class="text-xs font-weight-bold mb-0">{{ $item->storage_location }}</p>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">{{ $item->delivery_order }}</p>
                            </td>

                            <td class="align-middle text-sm">
                                @if($item->receive_flg == 1)
                                    <span class="badge badge-sm bg-gradient-success">Received</span>
                                @else
                                    <span class="badge badge-sm bg-gradient-warning">Waiting Receive</span>
                                @endif
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">{{ $item->container }}</p>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">{{ $item->case_number }}</p>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">{{ $item->box_id ?? '-' }}</p>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">{{ $item->temp_material ?? '-' }}</p>
                            </td>
                            <td class="text-end pe-4">
                                <span class="badge badge-sm bg-gradient-success">{{ number_format($item->quantity) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-4 text-sm text-secondary">
                                ไม่พบข้อมูล
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Pagination --}}
    <div class="card-footer d-flex justify-content-end py-3">
        {{-- withQueryString() จะช่วยรักษาค่า search และ per_page ไว้เมื่อเปลี่ยนหน้า --}}
        {{ $packingLists->withQueryString()->links() }}
    </div>
</div>
@endsection