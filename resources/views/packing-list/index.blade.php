@extends('layouts.app')

@section('title', 'Plan Report')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <h5 class="mb-3">Plan Report</h5>

            {{-- 1. ปรับปรุงฟอร์มค้นหาให้ตรงกับ Controller --}}
            <form action="{{ route('packing-list.index') }}" method="GET">
                <div class="row g-3 mb-3 align-items-end">
                    <div class="col-md-3">
                        <div class="input-group input-group-static">
                            <label>Plan No</label>
                            <input type="text" name="plan_no" class="form-control" value="{{ request('plan_no') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group input-group-static">
                            <label>Container No</label>
                            <input type="text" name="container_no" class="form-control"
                                value="{{ request('container_no') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group input-group-static">
                            <label>Material No</label>
                            <input type="text" name="material_no" class="form-control"
                                value="{{ request('material_no') }}">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('packing-list.index') }}" class="btn btn-secondary">Reset</a>
                        {{-- หมายเหตุ: การ Export อาจต้องปรับปรุงเพิ่มเติม --}}
                        <a href="{{ route('packing-list.export', request()->query()) }}" class="btn btn-success">Export</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    {{-- 2. แก้ไขส่วนหัวของตาราง --}}
                    <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Plan No
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Container
                                No</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Agent</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Material No
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Model</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Part Type
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Uloc
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pull
                                Type</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Quantity</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit
                            </th>
                        </tr>
                    </thead>
                    {{-- 3. แก้ไขส่วนเนื้อหาของตาราง --}}
                    <tbody>
                        @forelse ($packingLists as $key => $list)
                            <tr>
                                <td class="text-center px-2">
                                    <p class="text-xs font-weight-bold mb-0">{{ $packingLists->firstItem() + $key }}</p>
                                </td>
                                <td>

                                    {{-- ใช้ ?? เพื่อกำหนดค่า default ถ้า $list->plan_no เป็น null --}}
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->plan_no ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->container_no ?? 'N/A' }}</p>

                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->agent ?? 'N/A' }}</p>

                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->material_number ?? 'N/A' }}</p>

                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->model ?? 'N/A' }}</p>

                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->part_type ?? 'N/A' }}</p>

                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->uloc ?? 'N/A' }}</p>

                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->pull_type ?? 'N/A' }}</p>

                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->Qty ?? 0 }}</p>

                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->unit ?? 'N/A' }}</p>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center p-3">No data found matching your search criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            {{ $packingLists->withQueryString()->links() }}
        </div>
    </div>
@endsection
