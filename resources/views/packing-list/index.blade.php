@extends('layouts.app')

@section('title', 'Packing List')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <h5 class="mb-3">Packing List</h5>

            <form action="{{ route('packing-list.index') }}" method="GET">
                <div class="row g-3 mb-3 align-items-end">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group input-group-static">
                                    <label>Delivery Date From</label>
                                    <input type="date" name="delivery_date_from" class="form-control"
                                        value="{{ request('delivery_date_from') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group input-group-static">
                                    <label>Delivery Date To</label>
                                    <input type="date" name="delivery_date_to" class="form-control"
                                        value="{{ request('delivery_date_to') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="input-group input-group-static">
                            <label>Container No.</label>
                            <input type="text" class="form-control" name="container_no"
                                value="{{ request('container_no') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-static">
                            <label>Material Number</label>
                            <input type="text" class="form-control" name="material_number"
                                value="{{ request('material_number') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-sm mb-0">Search</button>
                        <button type="submit" name="export" value="1"
                            class="btn btn-success btn-sm mb-0">Export</button>
                        <a href="{{ route('packing-list.index') }}" class="btn btn-secondary btn-sm mb-0">Clear</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">
                                Storage Location</th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">
                                Item Number</th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">
                                Delivery Order</th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">
                                Delivery Item Number</th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">
                                Delivery Date</th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">
                                Container No</th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">
                                Agent</th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">
                                Material Number</th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">
                                Material Name</th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">
                                Unit</th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">
                                Case Number</th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">
                                Box ID</th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">
                                Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($packing_lists as $list)
                            <tr>
                                <td class="text-center px-2">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->storage_location }}</p>
                                </td>
                                <td class="text-center px-2">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->item_number }}</p>
                                </td>
                                <td class="text-center px-2">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->delivery_order }}</p>
                                </td>
                                <td class="text-center px-2">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->delivery_item_number }}</p>
                                </td>
                                <td class="text-center px-2">
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{ $list->delivery_date ? $list->delivery_date->format('d/m/Y') : 'N/A' }}</p>
                                </td>
                                <td class="text-center px-2">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->container?->container_no ?? 'N/A' }}
                                    </p>
                                </td>
                                <td class="text-center px-2">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->container?->agent ?? 'N/A' }}</p>
                                </td>
                                <td class="text-center px-2">
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{ $list->material?->material_number ?? 'N/A' }}</p>
                                </td>
                                <td class="text-center px-2">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->material?->material_name ?? 'N/A' }}
                                    </p>
                                </td>
                                <td class="text-center px-2">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->material?->unit ?? 'N/A' }}</p>
                                </td>
                                <td class="text-center px-2">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->case_number }}</p>
                                </td>
                                <td class="text-center px-2">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->box_id }}</p>
                                </td>
                                <td class="text-center px-2">
                                    <p class="text-xs font-weight-bold mb-0">{{ $list->quantity }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center p-3">No data found matching your search criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            {{ $packing_lists->withQueryString()->links() }}
        </div>
    </div>
@endsection
