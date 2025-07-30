@extends('layouts.blank')

@section('title', 'Live Dashboard')

@section('content')
  {{-- Header Row --}}
    <div class="row mb-4">
        <div class="col-lg-6 col-md-6 mb-4 mb-md-0">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <img src="{{ asset('assets/img/logo.jpg') }}" alt="Company Logo" style="max-height: 100px; width: auto;">
                    <div class="ms-3">
                        <h4 class="mb-0">Smart Tracking System</h4>
                        <p class="text-sm mb-0">Logistics & Container Management</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h2 class="mb-0 font-weight-bolder" id="live-clock"></h2>
                    <p class="mb-0 text-lg" id="live-date"></p>
                </div>
            </div>
        </div>
    </div>
    {{-- 1. Plan Status of the Month --}}
    <div class="row">
        <div class="col-12">
            <h5 class="mb-3">This Month's Plan Status</h5>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-rounded opacity-10">pending</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Pending</p>
                        <h4 class="mb-0">{{ $pendingCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-rounded opacity-10">task_alt</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Received</p>
                        <h4 class="mb-0">{{ $receivedCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-rounded opacity-10">local_shipping</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Shipped Out</p>
                        <h4 class="mb-0">{{ $shippedOutCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Yard Overview --}}
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="mb-3">Yard Overview</h5>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-rounded opacity-10">calendar_today</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Today's Pulling Plan</p>
                        <h4 class="mb-0">{{ $pullingTodayCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-secondary shadow-secondary text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-rounded opacity-10">view_in_ar</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Total Containers</p>
                        <h4 class="mb-0">{{ $totalContainers }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-rounded opacity-10">timer_off</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Expired Containers</p>
                        <h4 class="mb-0">{{ $expiredCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6>This Month's Container Activities</h6>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="activity-chart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>Yard Location Occupancy</h6>
                </div>
                <div class="card-body p-3 d-flex justify-content-center align-items-center">
                    <div class="chart" style="height: 250px;">
                        <canvas id="location-chart" class="chart-canvas"></canvas>
                    </div>
                </div>
                <div class="card-footer pt-0 p-3 text-center">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="w-50">
                            <h6 class="mb-0 text-sm">Available</h6>
                            <span class="text-lg font-weight-bold text-success">{{ $availableLocationsCount }}</span>
                        </div>
                        <div class="w-50">
                            <h6 class="mb-0 text-sm">Occupied</h6>
                            <span class="text-lg font-weight-bold text-danger">{{ $occupiedLocationsCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Today's Pulling Plan Table --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Today's Pulling Plan ({{ \Carbon\Carbon::today()->format('d M Y') }})</h6>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Order</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Container No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pullingTodayPlans as $plan)
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-3">{{ $plan->pulling_order }}</p>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 text-sm ps-2">{{ $plan->containerOrderPlan?->container?->container_no ?? 'N/A' }}</h6>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $plan->containerOrderPlan?->containerStock?->yardLocation?->location_code ?? 'N/A' }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if($plan->status == 1)
                                            <span class="badge badge-sm bg-gradient-secondary">Planned</span>
                                        @elseif($plan->status == 2)
                                            <span class="badge badge-sm bg-gradient-info">In Progress</span>
                                        @elseif($plan->status == 3)
                                            <span class="badge badge-sm bg-gradient-success">Completed</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center p-3">No pulling plans for today.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Remaining Free Time Table --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Containers Nearing Free Time Expiration</h6>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Container No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Expiration Date</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Days Remaining</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($expiringContainers as $plan)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <h6 class="mb-0 text-sm">{{ $plan->container->container_no }}</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $plan->containerStock->yardLocation->location_code ?? 'N/A' }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $plan->expiration_date?->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if($plan->remaining_free_time === 'Expired')
                                            <span class="badge badge-sm bg-gradient-danger">Expired</span>
                                        @else
                                            <span class="text-xs font-weight-bold">{{ $plan->remaining_free_time }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center p-3">No containers nearing expiration.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  {{-- เพิ่มตารางใหม่สำหรับ Available Locations by Type --}}
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Available Locations by Type</h6>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location Type</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Available Slots</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($availableLocationsByType as $type)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <h6 class="mb-0 text-sm">{{ $type->name }}</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-success font-weight-bold">{{ $type->available }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center p-3">No location types found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        // Doughnut Chart for Location Occupancy
        var ctx1 = document.getElementById("location-chart").getContext("2d");
        new Chart(ctx1, {
            type: "doughnut",
            data: {
                labels: ['Available', 'Occupied'],
                datasets: [{
                    label: "Locations",
                    weight: 9,
                    cutout: '70%',
                    backgroundColor: ['#2dce89', '#fb6340'],
                    data: [{{ $availableLocationsCount }}, {{ $occupiedLocationsCount }}],
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    }
                },
            },
        });

        // Bar Chart for Container Activities
        var ctx2 = document.getElementById("activity-chart").getContext("2d");
        new Chart(ctx2, {
            type: "bar",
            data: {
                labels: {!! json_encode($activityLabels) !!},
                datasets: [{
                    label: "Transactions",
                    backgroundColor: "#3A416F",
                    data: {!! json_encode($activityData) !!},
                    maxBarThickness: 40
                }, ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            stepSize: 1, // Ensure y-axis shows whole numbers
                            beginAtZero: true,
                        },
                    },
                },
            },
        });

	 // Live Clock
        function updateClock() {
            const now = new Date();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };

            document.getElementById('live-date').textContent = now.toLocaleDateString('en-US', dateOptions);
            document.getElementById('live-clock').textContent = now.toLocaleTimeString('en-GB', timeOptions);
        }

        setInterval(updateClock, 1000);
        updateClock(); // Initial call
        // Auto-refresh the page every 5 minutes
        setTimeout(function(){
           window.location.reload(1);
        }, 300000);
    });
</script>
@endpush
