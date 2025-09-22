@extends('layouts.app')

@section('title', 'Upload New File')

@section('content')
    <style>
        #files-input::file-selector-button {
            margin-right: 12px;
            padding-left: 12px;
            padding-right: 12px;
        }
    </style>

    <div class="card">
        <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Upload New Files</h5>
                <div>
                    <a href="{{ route('files.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-success">Upload</button>
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
                        <div class="row">
                            <div class="col-10">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <div class="input-group input-group-outline">
                                        <select class="form-control" name="category">
                                            <option value="">-- Select Category --</option>
                                            <option value="accounting">Accounting</option>
                                            <option value="import_export">Import & Export</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Document Number (Optional)</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="document_number"
                                    value="{{ old('document_number') }}">
                            </div>
                            <p class="text-sm text-muted mt-1">Use one document number to group multiple files.</p>
                        </div>

                        <div class="mb-3">
                            <label for="files" class="form-label">Select files to upload (you can select multiple
                                files)</label>
                            <div class="input-group input-group-outline">
                                <input class="form-control" type="file" name="files[]" id="files-input" multiple
                                    required>
                            </div>
                            <p class="text-sm text-muted mt-1">Max 10 files, 20MB per file.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
