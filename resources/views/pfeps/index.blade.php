@extends('layouts.app')

@section('title', 'PFEP Master')

@section('content')
    <div class="card">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
                    <h5 class="mb-3 mb-md-0">PFEP Master</h5>
                    <div class="d-flex align-items-center">
                        <form action="{{ route('materials.index') }}" method="GET" class="me-2">
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                    placeholder="Search...">
                            </div>
                        </form>
                        <a href="{{ route('materials.create') }}" class="btn btn-dark mb-0">Add New Material</a>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="p-4">
                    @include('layouts.partials.alerts')
                </div>
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Material
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Model
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Part
                                    Type
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Uloc
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pull
                                    Type
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Line
                                    Side
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pfeps as $pfep)
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-2">{{ $pfep->material->material_number }}
                                        </p>
                                        <p class="text-xs text-secondary mb-0 px-2">{{ $pfep->material->material_name }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-2">{{ $pfep->model }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-2">{{ $pfep->part_type }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-2">{{ $pfep->uloc }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-2">{{ $pfep->pull_type }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-2">{{ $pfep->line_side }}</p>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('pfeps.edit', $pfep->id) }}"
                                            class="btn btn-info btn-sm mb-0">Edit</a>
                                        <button type="button" class="btn btn-danger btn-sm mb-0" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal-{{ $pfep->id }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                @include('pfeps.partials.delete-modal', ['pfep' => $pfep])
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center p-3">No PFEP records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <form action="{{ route('pfeps.index') }}" method="GET" class="w-auto">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="per_page" class="form-select ps-2" onchange="this.form.submit()">
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 page</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 page</option>
                        <option value="75" {{ $perPage == 75 ? 'selected' : '' }}>75 page</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 page</option>
                    </select>
                </form>
                {{ $pfeps->withQueryString()->links() }}
            </div>
        </div>
    @endsection
