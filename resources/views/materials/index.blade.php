@extends('layouts.app')

@section('title', 'Material Management')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Material Master</h5>
                <a href="{{ route('materials.create') }}" class="btn btn-dark mb-0">Add New Material</a>
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
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Material
                                Number</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Material
                                Name</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($materials as $material)
                            <tr>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">{{ $material->material_number }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2">{{ $material->material_name }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $material->unit }}</p>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('materials.edit', $material->id) }}"
                                        class="btn btn-info btn-sm mb-0">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm mb-0" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal-{{ $material->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @include('materials.partials.delete-modal', ['material' => $material])
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-3">No materials found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
            {{-- ✅ FIX: ปรับความกว้างของฟอร์ม --}}
            <form action="{{ route('materials.index') }}" method="GET" class="w-auto">
                <select name="per_page" class="form-select ps-2" onchange="this.form.submit()">
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 page</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 page</option>
                    <option value="75" {{ $perPage == 75 ? 'selected' : '' }}>75 page</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 page</option>
                </select>
            </form>
            {{ $materials->withQueryString()->links() }}
        </div>
    </div>
@endsection
