@csrf
{{-- Location Code & Type --}}
<div class="row">
    <div class="col-md-12 mb-4">
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

{{-- Status --}}
<div class="mb-3">
    <label class="form-label">Status</label>
    <div class="form-check form-switch d-flex align-items-center p-0">
        <input class="form-check-input ms-0" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $yardLocation->is_active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label mb-0 ms-3" for="is_active">Active</label>
    </div>
</div>

{{-- เพิ่มปุ่ม Clear --}}
<div class="mt-4">
    <button type="button" class="btn btn-light" id="clear-form-btn">Clear Form</button>
</div>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const zoneSelect = document.getElementById('zone_id');
        const areaSelect = document.getElementById('area_id');
        const binSelect = document.getElementById('bin_id');
        const locationTypeSelect = document.querySelector('select[name="location_type_id"]');
        const statusCheckbox = document.getElementById('is_active');
        const clearButton = document.getElementById('clear-form-btn');

        // ฟังก์ชันสำหรับล้างข้อมูลในฟอร์ม
        function clearForm() {
            zoneSelect.selectedIndex = 0;
            areaSelect.selectedIndex = 0;
            binSelect.selectedIndex = 0;
            locationTypeSelect.selectedIndex = 0;
            statusCheckbox.checked = true; // ตั้งค่ากลับเป็น Active
        }

        // Event listener สำหรับปุ่ม Clear
        clearButton.addEventListener('click', function() {
            clearForm();
        });
    });
</script>
@endpush
