@csrf
{{-- We'll put all fields in a single row for a more compact layout --}}
<div class="row">
    {{-- FIX: Changed width from col-md-6 to col-md-4 --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Material Number</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="material_number"
                value="{{ old('material_number', $material->material_number ?? '') }}" required>
        </div>
        @error('material_number')
            <p class="text-danger text-xs pt-1"> {{ $message }} </p>
        @enderror
    </div>

    {{-- FIX: Changed width from col-md-6 to col-md-5 (name can be longer) --}}
    <div class="col-md-5 mb-3">
        <label class="form-label">Material Name</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="material_name"
                value="{{ old('material_name', $material->material_name ?? '') }}" required>
        </div>
        @error('material_name')
            <p class="text-danger text-xs pt-1"> {{ $message }} </p>
        @enderror
    </div>

    {{-- FIX: Changed width from col-md-6 to col-md-2 (unit is short) --}}
    <div class="col-md-2 mb-3">
        <label class="form-label">Unit</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="unit" value="{{ old('unit', $material->unit ?? '') }}">
        </div>
        @error('unit')
            <p class="text-danger text-xs pt-1"> {{ $message }} </p>
        @enderror
    </div>
</div>

{{-- This part is for the buttons, you should add it if it's not in your create/edit files --}}
{{-- <div class="mt-4">
    <button type="submit" class="btn btn-primary">{{ $submitButtonText ?? 'Submit' }}</button>
    <a href="{{ route('materials.index') }}" class="btn btn-secondary">Cancel</a>
</div> --}}
