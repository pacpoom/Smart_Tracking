@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Container Open-ReturnCy</h1>
    <p class="mb-4">เลือกรายการ Order Pulling ที่ต้องการยืนยันการเปิดตู้คอนเทนเนอร์</p>

    <!-- Alerts -->
    @include('layouts.partials.alerts')

    <!-- Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">ตัวกรองข้อมูล</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('container-open-return-cy.index') }}" class="form-inline">
                <div class="form-group mr-3 mb-2">
                    <label for="open_type" class="mr-2">Container Open Type:</label>
                    <select name="open_type" id="open_type" class="form-control">
                        <option value="All" {{ $openType == 'All' ? 'selected' : '' }}>All</option>
                        <option value="Pull" {{ $openType == 'Pull' ? 'selected' : '' }}>Pull</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mb-2">ค้นหา</button>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">รายการ Order Pulling</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('container-open-return-cy.store') }}" method="POST" id="confirm-form">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>SO</th>
                                <th>Container No.</th>
                                <th>Size/Type</th>
                                <th>Vendor</th>
                                <th>Seal No</th>
                                <th>Status</th>
                                <th>Plan Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pullingPlans as $plan)
                                <tr>
                                    <td><input type="checkbox" name="pulling_plan_ids[]" value="{{ $plan->id }}" class="plan-checkbox"></td>
                                    <td>{{ $plan->so_no }}</td>
                                    <td>{{ $plan->container->container_no ?? 'N/A' }}</td>
                                    <td>{{ $plan->container->sizeType->name ?? 'N/A' }}</td>
                                    <td>{{ $plan->vendor->name ?? 'N/A' }}</td>
                                    <td>{{ $plan->seal_no }}</td>
                                    <td><span class="badge badge-warning">{{ $plan->status }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($plan->plan_date)->format('d-m-Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">ไม่พบรายการที่รอการยืนยัน</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pullingPlans->isNotEmpty())
                    <button type="submit" class="btn btn-success mt-3"><i class="fas fa-check-circle"></i> ยืนยันรายการที่เลือก</button>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ฟังก์ชันสำหรับ 'Select All' checkbox
        const selectAllCheckbox = document.getElementById('select-all');
        const planCheckboxes = document.querySelectorAll('.plan-checkbox');

        if(selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                planCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }

        // ฟังก์ชันยืนยันก่อน submit form
        const confirmForm = document.getElementById('confirm-form');
        if(confirmForm) {
            confirmForm.addEventListener('submit', function (e) {
                const checkedCheckboxes = document.querySelectorAll('.plan-checkbox:checked').length;
                if (checkedCheckboxes === 0) {
                    e.preventDefault();
                    alert('กรุณาเลือกอย่างน้อย 1 รายการเพื่อยืนยัน');
                    return;
                }

                if (!confirm('คุณแน่ใจหรือไม่ว่าต้องการยืนยันรายการที่เลือก?')) {
                    e.preventDefault();
                }
            });
        }
    });
</script>
@endpush
