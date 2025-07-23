@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Part Number</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="part_number" value="{{ old('part_number', $part->part_number ?? '') }}" required>
        </div>
        @error('part_number') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Unit</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="unit" value="{{ old('unit', $part->unit ?? '') }}" placeholder="e.g., PCS, KG, SET">
        </div>
        @error('unit') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Part Name (TH)</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="part_name_thai" value="{{ old('part_name_thai', $part->part_name_thai ?? '') }}">
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Part Name (EN)</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="part_name_eng" value="{{ old('part_name_eng', $part->part_name_eng ?? '') }}">
        </div>
    </div>
</div>
{{-- เพิ่มส่วนนี้เข้ามา --}}
<div class="mb-3">
    <label class="form-label">Model No.</label>
    <div class="input-group input-group-outline">
        <textarea class="form-control" name="model_no" rows="3">{{ old('model_no', $part->model_no ?? '') }}</textarea>
    </div>
</div>
