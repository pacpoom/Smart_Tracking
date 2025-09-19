@csrf
<div class="row">
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

    {{-- ✅ FIX: Changed the 'Unit' input to a dropdown select --}}
    <div class="col-md-2 mb-3">
        <label class="form-label">Unit</label>
        <div class="input-group input-group-outline">
            <select class="form-control" name="unit">
                <option value="">-- Select --</option>
                @php
                    $units = ['EA', 'M2', 'KG', 'G', 'M', 'L'];
                    $selectedValue = old('unit', $material->unit ?? '');
                @endphp
                @foreach ($units as $unit)
                    <option value="{{ $unit }}" {{ $selectedValue == $unit ? 'selected' : '' }}>
                        {{ $unit }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('unit')
            <p class="text-danger text-xs pt-1"> {{ $message }} </p>
        @enderror
    </div>
</div>
