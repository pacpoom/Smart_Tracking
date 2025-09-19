@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="input-group input-group-static">
            <label>Material Number</label>
            <input type="text" class="form-control" name="material_number"
                value="{{ old('material_number', $pfep->material->material_number ?? '') }}" required>
        </div>
        @error('material_number')
            <p class="text-danger text-xs pt-1"> {{ $message }} </p>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <div class="input-group input-group-static">
            <label>Model</label>
            <input type="text" class="form-control" name="model" value="{{ old('model', $pfep->model ?? '') }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="input-group input-group-static">
            <label for="part-type-select">Part Type</label>
            <select class="form-control ps-2" id="part-type-select" name="part_type">
                <option value="">-- Select --</option>
                @foreach ($part_types as $part_type)
                    <option value="{{ $part_type }}"
                        {{ old('part_type', $pfep->part_type ?? '') == $part_type ? 'selected' : '' }}>
                        {{ $part_type }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="input-group input-group-static">
            <label>Uloc</label>
            <input type="text" class="form-control" name="uloc" value="{{ old('uloc', $pfep->uloc ?? '') }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="input-group input-group-static">
            <label for="pull-type-select">Pull Type</label>
            <select class="form-control ps-2" id="pull-type-select" name="pull_type">
                <option value="">-- Select --</option>
                @foreach ($pull_types as $pull_type)
                    <option value="{{ $pull_type }}"
                        {{ old('pull_type', $pfep->pull_type ?? '') == $pull_type ? 'selected' : '' }}>
                        {{ $pull_type }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="input-group input-group-static">
            <label for="line-side-select">Line Side</label>
            <select class="form-control ps-2" id="line-side-select" name="line_side">
                <option value="">-- Select --</option>
                @foreach ($line_sides as $line_side)
                    <option value="{{ $line_side }}"
                        {{ old('line_side', $pfep->line_side ?? '') == $line_side ? 'selected' : '' }}>
                        {{ $line_side }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
