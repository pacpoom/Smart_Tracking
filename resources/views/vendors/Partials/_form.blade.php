@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Vendor Code</label>
        <div class="input-group input-group-outline">
            {{-- ลบ attribute 'required' ออก --}}
            <input type="text" class="form-control" name="vendor_code" value="{{ old('vendor_code', $vendor->vendor_code ?? '') }}">
        </div>
        {{-- เพิ่มคำแนะนำ --}}
        <p class="text-sm text-muted mt-1">Leave blank to auto-generate.</p>
        @error('vendor_code') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Vendor Name</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="name" value="{{ old('name', $vendor->name ?? '') }}" required>
        </div>
        @error('name') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Address</label>
    <div class="input-group input-group-outline">
        <textarea class="form-control" name="address" rows="3">{{ old('address', $vendor->address ?? '') }}</textarea>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Contact Person</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="contact_person" value="{{ old('contact_person', $vendor->contact_person ?? '') }}">
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Phone</label>
        <div class="input-group input-group-outline">
            <input type="tel" class="form-control" name="phone" value="{{ old('phone', $vendor->phone ?? '') }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <div class="input-group input-group-outline">
            <input type="email" class="form-control" name="email" value="{{ old('email', $vendor->email ?? '') }}">
        </div>
        @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status</label>
        <div class="form-check form-switch d-flex align-items-center">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $vendor->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label mb-0 ms-3" for="is_active">Active</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Register Date</label>
        <div class="input-group input-group-outline">
            <input type="date" class="form-control" name="register_date" value="{{ old('register_date', isset($vendor) ? $vendor->register_date?->format('Y-m-d') : '') }}">
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Expire Date</label>
        <div class="input-group input-group-outline">
            <input type="date" class="form-control" name="expire_date" value="{{ old('expire_date', isset($vendor) ? $vendor->expire_date?->format('Y-m-d') : '') }}">
        </div>
    </div>
</div>
<div class="mb-3">
    <label for="attachment" class="form-label">Attachment</label>
    <div class="input-group input-group-outline">
        <input class="form-control" type="file" name="attachment">
    </div>
    @error('attachment') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
    @if(isset($vendor) && $vendor->attachment_path)
        <p class="text-sm mt-2">Current file: <a href="{{ route('vendors.download', $vendor->id) }}">{{ basename($vendor->attachment_path) }}</a></p>
    @endif
</div>
