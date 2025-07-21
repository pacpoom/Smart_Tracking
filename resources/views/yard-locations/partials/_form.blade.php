@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Location Code</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="location_code" value="{{ old('location_code', $yardLocation->location_code ?? '') }}" required>
        </div>
        @error('location_code') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Location Type</label>
        <div class="input-group input-group-outline">
            <select class="form-control" name="location_type_id">
                <option value="">-- Select Type --</option>
                @foreach($locationTypes as $id => $name)
                    <option value="{{ $id }}" {{ (old('location_type_id', $yardLocation->location_type_id ?? '') == $id) ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Zone</label>
        <div class="input-group input-group-outline">
             <select class="form-control" name="zone_id">
                <option value="">-- Select Zone --</option>
                @foreach($zones as $id => $name)
                    <option value="{{ $id }}" {{ (old('zone_id', $yardLocation->zone_id ?? '') == $id) ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Area</label>
        <div class="input-group input-group-outline">
            <select class="form-control" name="area_id">
                <option value="">-- Select Area --</option>
                @foreach($areas as $id => $name)
                    <option value="{{ $id }}" {{ (old('area_id', $yardLocation->area_id ?? '') == $id) ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Bin</label>
        <div class="input-group input-group-outline">
            <select class="form-control" name="bin_id">
                <option value="">-- Select Bin --</option>
                @foreach($bins as $id => $name)
                    <option value="{{ $id }}" {{ (old('bin_id', $yardLocation->bin_id ?? '') == $id) ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Status</label>
    <div class="form-check form-switch d-flex align-items-center">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $yardLocation->is_active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label mb-0 ms-3" for="is_active">Active</label>
    </div>
</div>
