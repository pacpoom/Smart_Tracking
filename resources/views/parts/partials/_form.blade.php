@csrf
<div class="row">
    {{-- Main Fields Column --}}
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Location Details</h5>
            </div>
            <div class="card-body">
                {{-- Location Code --}}
                <div class="mb-4">
                    <label class="form-label">Location Code</label>
                    <div class="input-group input-group-outline">
                        <input type="text" class="form-control" id="location_code" name="location_code" value="{{ old('location_code', $yardLocation->location_code ?? '') }}" readonly>
                    </div>
                    @error('location_code') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                </div>
                
                {{-- Location Hierarchy --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Zone</label>
                        <div class="input-group input-group-outline">
                             <select class="form-control location-part" id="zone_id" name="zone_id">
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
                            <select class="form-control location-part" id="area_id" name="area_id">
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
                            <select class="form-control location-part" id="bin_id" name="bin_id">
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
            </div>
        </div>
    </div>

    {{-- Side Fields Column --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Properties</h5>
            </div>
            <div class="card-body">
                {{-- Location Type --}}
                <div class="mb-4">
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

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch d-flex align-items-center p-0">
                        <input class="form-check-input ms-0" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $yardLocation->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label mb-0 ms-3" for="is_active">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const zoneSelect = document.getElementById('zone_id');
        const areaSelect = document.getElementById('area_id');
        const binSelect = document.getElementById('bin_id');
        const locationCodeInput = document.getElementById('location_code');
        const locationCodeWrapper = locationCodeInput.parentElement;

        function updateLocationCode() {
            const zoneText = zoneSelect.options[zoneSelect.selectedIndex]?.text.replace(/-- Select Zone --/g, '').trim();
            const areaText = areaSelect.options[areaSelect.selectedIndex]?.text.replace(/-- Select Area --/g, '').trim();
            const binText = binSelect.options[binSelect.selectedIndex]?.text.replace(/-- Select Bin --/g, '').trim();

            const parts = [zoneText, areaText, binText].filter(Boolean); // Filter out empty strings
            const generatedCode = parts.join('-');

            locationCodeInput.value = generatedCode;

            // This is for the Material Dashboard floating label
            if (generatedCode) {
                locationCodeWrapper.classList.add('is-filled');
            } else {
                locationCodeWrapper.classList.remove('is-filled');
            }
        }

        [zoneSelect, areaSelect, binSelect].forEach(select => {
            select.addEventListener('change', updateLocationCode);
        });

        // Initial call to set the value on page load (for edit form)
        updateLocationCode();
    });
</script>
@endpush
