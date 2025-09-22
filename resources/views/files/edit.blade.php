@extends('layouts.app')

@section('title', 'Edit File')

@section('content')
    <div class="card">
        <form action="{{ route('files.update', $file->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Edit File</h5>
                <div>
                    <a href="{{ route('files.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger text-white">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">

                        {{-- === จุดที่แก้ไข: ครอบ Dropdown ด้วย col-10 เพื่อให้สั้นลง === --}}
                        <div class="row">
                            <div class="col-10">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <div class="input-group input-group-outline">
                                        <select class="form-control" name="category">
                                            <option value="">-- Select Category --</option>
                                            <option value="accounting"
                                                {{ old('category', $file->category) == 'accounting' ? 'selected' : '' }}>
                                                Accounting</option>
                                            <option value="import_export"
                                                {{ old('category', $file->category) == 'import_export' ? 'selected' : '' }}>
                                                Import & Export</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- === สิ้นสุดส่วนที่แก้ไข === --}}

                        <div class="mb-3">
                            <label class="form-label">Document Number</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="document_number"
                                    value="{{ old('document_number', $file->document_number) }}">
                            </div>
                            @error('document_number')
                                <p class="text-danger text-xs pt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Original Filename</label>
                            <div class="input-group">
                                <div class="input-group input-group-outline">
                                    <input type="text" class="form-control" name="filename_no_ext"
                                        value="{{ old('filename_no_ext', pathinfo($file->original_filename, PATHINFO_FILENAME)) }}"
                                        required>
                                </div>
                                <span
                                    class="input-group-text px-3">.{{ pathinfo($file->original_filename, PATHINFO_EXTENSION) }}</span>
                            </div>
                            @error('filename_no_ext')
                                <p class="text-danger text-xs pt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <p class="text-sm text-muted">Hashed Filename: {{ $file->hashed_filename }}</p>
                        <p class="text-sm text-muted">Uploaded by: {{ $file->user->name }} on
                            {{ $file->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
