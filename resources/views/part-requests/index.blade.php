@extends('layouts.app')

@section('title', 'Part Request List')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <h5>Part Request List</h5>
        </div>
        <div class="card-body">
            {{-- Advanced Search Form --}}
            <form action="{{ route('part-requests.index') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <div class="input-group input-group-outline my-3 {{ request('part_number') ? 'is-filled' : '' }}">
                            <label class="form-label">Part Number</label>
                            <input type="text" class="form-control" name="part_number"
                                value="{{ request('part_number') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group input-group-outline my-3">
                            <select class="form-control" name="status">
                                <option value="">-- All Statuses --</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                                <option value="delivery" {{ request('status') == 'delivery' ? 'selected' : '' }}>Delivery
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-outline my-3">
                            <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}"
                                title="Required Date From">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-outline my-3">
                            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}"
                                title="Required Date To">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark">Search</button>
                        {{-- Export Button --}}
                        <a href="{{ route('part-requests.export', request()->query()) }}"
                            class="btn btn-success ms-2">Export</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Part Number
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Dealer Name
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Requested
                                By</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Qty
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Required Date</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Delivery Date</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Arrival Date</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Status</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Documents</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $request)
                            <tr>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">{{ $request->part->part_number }}</p>
                                    <p class="text-xs text-secondary mb-0 px-2">{{ $request->part->part_name_eng }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">{{ $request->foc_no }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $request->user->name }}</p>
                                </td>
                                <td class="align-middle text-center"><span
                                        class="text-secondary text-xs font-weight-bold">{{ $request->quantity }}</span>
                                </td>
                                <td class="align-middle text-center"><span
                                        class="text-secondary text-xs font-weight-bold">{{ $request->required_date->format('d/m/Y') }}</span>
                                </td>
                                <td class="align-middle text-center"><span
                                        class="text-secondary text-xs font-weight-bold">{{ $request->delivery_date?->format('d/m/Y') }}</span>
                                </td>
                                <td class="align-middle text-center"><span
                                        class="text-secondary text-xs font-weight-bold">{{ $request->arrival_date?->format('d/m/Y') }}</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    @if ($request->status == 'approved')
                                        <span class="badge badge-sm bg-gradient-success">Approved</span>
                                    @elseif($request->status == 'rejected')
                                        <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                                    @elseif($request->status == 'delivery')
                                        <span class="badge badge-sm bg-gradient-info">Delivery</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    @if ($request->attachment_path)
                                        <a href="{{ route('part-requests.download', $request->id) }}"
                                            class="btn btn-link text-secondary mb-0 p-0"
                                            title="Download Request Attachment">
                                            <i class="material-symbols-rounded">attach_file</i>
                                        </a>
                                    @endif
                                    @if ($request->delivery_document_path)
                                        <a href="{{ route('part-requests.downloadDeliveryDocument', $request->id) }}"
                                            class="btn btn-link text-info mb-0 p-0" title="Download Delivery Document">
                                            <i class="material-symbols-rounded">local_shipping</i>
                                        </a>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    @can('approve part requests')
                                        <a href="{{ route('part-requests.edit', $request->id) }}"
                                            class="btn btn-link text-secondary mb-0 p-0" title="Update Status">
                                            <i class="material-symbols-rounded">edit_note</i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center p-3">No part requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            {{ $requests->withQueryString()->links() }}
        </div>
    </div>
@endsection
