@extends('layouts.app')

@section('title', 'File Uploads')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
                <h5 class="mb-3 mb-md-0">Uploaded Files</h5>
                <a href="{{ route('files.create') }}" class="btn btn-dark mb-0">Upload New File</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('files.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <div class="input-group input-group-outline {{ request('search') ? 'is-filled' : '' }}">
                            <label class="form-label">Search by Filename or Doc No...</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-outline">
                            <select class="form-control" name="category">
                                <option value="">-- All Categories --</option>
                                <option value="accounting" {{ request('category') == 'accounting' ? 'selected' : '' }}>
                                    Accounting</option>
                                <option value="import_export"
                                    {{ request('category') == 'import_export' ? 'selected' : '' }}>Import & Export</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date From</label>
                        <div class="input-group input-group-outline">
                            <input type="date" class="form-control" name="start_date"
                                value="{{ request('start_date') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date To</label>
                        <div class="input-group input-group-outline">
                            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex">
                        <button class="btn btn-dark mb-0 me-2" type="submit">Search</button>
                        <a href="{{ route('files.index') }}" class="btn btn-secondary mb-0">Clear</a>
                    </div>
                </div>
            </form>

            <div class="p-4">
                @include('layouts.partials.alerts')
            </div>

            <div class="table-responsive p-0 mt-4">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            {{-- === แก้ไข: เพิ่ม text-center ให้ทุกคอลัมน์ === --}}
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Doc
                                No.</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Category</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Filename</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Uploaded By</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Upload Date</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($files as $file)
                            <tr>
                                {{-- === แก้ไข: เพิ่ม align-middle text-center ให้ทุกคอลัมน์ === --}}
                                <td class="align-middle text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $file->document_number ?? '-' }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{ Str::title(str_replace('_', ' ', $file->category)) ?: '-' }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $file->original_filename }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $file->user->name ?? 'N/A' }}</p>
                                </td>
                                <td class="align-middle text-center"><span
                                        class="text-secondary text-xs font-weight-bold">{{ $file->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('files.download', $file->id) }}"
                                        class="btn btn-link text-secondary mb-0" title="Download">
                                        <i class="material-symbols-rounded">download</i>
                                    </a>
                                    <a href="{{ route('files.edit', $file->id) }}" class="btn btn-link text-dark mb-0"
                                        title="Edit">
                                        <i class="material-symbols-rounded">edit</i>
                                    </a>
                                    <button type="button" class="btn btn-link text-danger mb-0" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal-{{ $file->id }}" title="Delete">
                                        <i class="material-symbols-rounded">delete</i>
                                    </button>
                                </td>
                            </tr>
                            @include('files.partials.delete-modal', ['file' => $file])
                        @empty
                            <td colspan="6" class="text-center p-3">No files found.</td>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            {{ $files->withQueryString()->links() }}
        </div>
    </div>
@endsection
