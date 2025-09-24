@extends('layouts.app')

@section('title', 'Production Plan Details')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Plan Details: {{ $productionPlan->plan_no }}</h5>
                <p class="text-sm mb-0">
                    Created on {{ $productionPlan->created_at->format('M d, Y') }} by {{ $productionPlan->user->name }}
                </p>
            </div>
            <a href="{{ route('production-plans.index') }}" class="btn btn-secondary mb-0">Back to List</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Plan Information</h6>
                <ul class="list-group">
                    <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">VC Code:</strong> &nbsp; {{ $productionPlan->vcMaster->vc_code }}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Model:</strong> &nbsp; {{ $productionPlan->vcMaster->model }}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Production Order:</strong> &nbsp; {{ number_format($productionPlan->production_order) }} units</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Production Date:</strong> &nbsp; {{ $productionPlan->production_date->format('Y-m-d') }}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Status:</strong> &nbsp; <span class="badge bg-gradient-info">{{ ucfirst($productionPlan->status) }}</span></li>
                </ul>
            </div>
        </div>

        <hr class="horizontal dark mt-4 mb-3">

        <h6>Required Materials</h6>
        <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material Number</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Material Name</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-end">Required Qty</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-end">Stock Qty</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-end">CY Qty</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-end">Balance</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_array($productionPlan->details))
                        @foreach($productionPlan->details as $detail)
                            @php
                                $stockQty = $detail['stock_qty'] ?? 0;
                                $cyQty = $detail['cy_qty'] ?? 0;
                                $requiredQty = $detail['required_qty'] ?? 0;
                                $balance = ($stockQty + $cyQty) - $requiredQty;
                            @endphp
                            <tr>
                                <td><p class="text-xs font-weight-bold mb-0">{{ $detail['material_number'] ?? 'N/A' }}</p></td>
                                <td><p class="text-xs font-weight-bold mb-0">{{ $detail['material_name'] ?? 'N/A' }}</p></td>
                                <td class="text-end"><p class="text-xs font-weight-bold mb-0">{{ number_format($requiredQty, 3) }}</p></td>
                                <td class="text-end"><p class="text-xs font-weight-bold mb-0">{{ number_format($stockQty, 3) }}</p></td>
                                <td class="text-end"><p class="text-xs font-weight-bold mb-0">{{ number_format($cyQty, 3) }}</p></td>
                                <td class="text-end">
                                    <p class="text-xs font-weight-bold mb-0 {{ $balance < 0 ? 'text-danger' : '' }}">
                                        {{ number_format($balance, 3) }}
                                    </p>
                                </td>
                                <td class="text-center">
                                    @if($stockQty >= $requiredQty)
                                        <span class="badge bg-gradient-success">Sufficient</span>
                                    @else
                                        <span class="badge bg-gradient-danger">Insufficient</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">No material details available.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection