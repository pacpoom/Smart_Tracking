@csrf
<div class="row">
    <div class="col-md-12 mb-3">
        <label class="form-label">Select Material</label>
        <select class="form-control" id="material-select" name="material_id" required>
            {{-- Pre-populate for edit form --}}
            @if (isset($pfep) && $pfep->material)
                <option value="{{ $pfep->material_id }}" selected>
                    {{ $pfep->material->material_number }} - {{ $pfep->material->material_name }}
                </option>
            @endif
        </select>
        @error('material_id')
            <p class="text-danger text-xs pt-1"> {{ $message }} </p>
        @enderror
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Model</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="model" value="{{ old('model', $pfep->model ?? '') }}">
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Part Type</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="part_type"
                value="{{ old('part_type', $pfep->part_type ?? '') }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Uloc</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="uloc" value="{{ old('uloc', $pfep->uloc ?? '') }}">
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Pull Type</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="pull_type"
                value="{{ old('pull_type', $pfep->pull_type ?? '') }}">
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Line Side</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="line_side"
                value="{{ old('line_side', $pfep->line_side ?? '') }}">
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Initialize Select2 for Material Search
        $(document).ready(function() {
            $('#material-select').select2({
                theme: 'bootstrap-5',
                placeholder: 'Type to search for a material...',
                ajax: {
                    // We will create this route later
                    url: '{{ route('materials.search') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.material_number + ' - ' + item.material_name
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endpush
